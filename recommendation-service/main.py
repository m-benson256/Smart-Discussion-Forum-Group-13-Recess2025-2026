from fastapi import FastAPI
import requests
import pandas as pd
from sklearn.metrics.pairwise import cosine_similarity

app = FastAPI()

@app.get("/health")
def health_check():
    return {"status": "ok"}

@app.get("/debug/interactions")
def debug_interactions():
    response = requests.get("http://127.0.0.1:8000/internal/interaction-data")
    data = response.json()
    return {"count": len(data), "sample": data[:5]}

@app.get("/recommendations/{user_id}")
def get_recommendations(user_id: int):
    response = requests.get("http://127.0.0.1:8000/internal/interaction-data")
    data = response.json()

    if not data:
        return {"recommended_topic_ids": []}

    df = pd.DataFrame(data)
    matrix = df.pivot_table(index='user_id', columns='topic_id', values='view_count', fill_value=0)

    if user_id not in matrix.index:
        return {"recommended_topic_ids": []}

    similarity = cosine_similarity(matrix)
    similarity_df = pd.DataFrame(similarity, index=matrix.index, columns=matrix.index)

    similar_users = similarity_df[user_id].drop(user_id).sort_values(ascending=False)

    if similar_users.empty:
        return {"recommended_topic_ids": []}

    top_similar_user = similar_users.index[0]

    this_user_topics = set(matrix.columns[matrix.loc[user_id] > 0])
    similar_user_topics = set(matrix.columns[matrix.loc[top_similar_user] > 0])

    recommended = list(similar_user_topics - this_user_topics)

    return {"recommended_topic_ids": recommended}