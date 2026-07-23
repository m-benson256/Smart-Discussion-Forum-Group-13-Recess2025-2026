package org.example;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import com.fasterxml.jackson.annotation.JsonProperty;

@JsonIgnoreProperties(ignoreUnknown = true)
public class StudentScore {
    @JsonProperty("student_name")
    private String student_name;
    @JsonProperty("message_count")
    private Integer message_count;
    @JsonProperty("reactions_given_count")
    private Integer reactions_given_count;
    @JsonProperty("score")
    private Integer score;
    @JsonProperty("max_score")
    private Integer max_score;

    public String getStudentName() { return student_name; }
    public Integer getMessageCount() { return message_count; }
    public Integer getReactionsGivenCount() { return reactions_given_count; }
    public Integer getScore() { return score; }
    public Integer getMaxScore() { return max_score; }
}