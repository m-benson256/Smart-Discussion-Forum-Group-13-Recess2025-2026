package org.example;

import java.util.List;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;

@JsonIgnoreProperties(ignoreUnknown = true)
public class Question {
    private Long id;
    private String type;            // "mcq" | "tf" | "sa"
    private String prompt;
    private String correct_answer;
    private List<QuestionOption> options;

    public Long getId() { return id; }
    public void setId(Long id) { this.id = id; }
    public String getType() { return type; }
    public void setType(String type) { this.type = type; }
    public String getPrompt() { return prompt; }
    public void setPrompt(String prompt) { this.prompt = prompt; }
    public String getCorrectAnswer() { return correct_answer; }
    public void setCorrectAnswer(String correct_answer) { this.correct_answer = correct_answer; }
    public List<QuestionOption> getOptions() { return options; }
    public void setOptions(List<QuestionOption> options) { this.options = options; }
}