package org.example;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;

@JsonIgnoreProperties(ignoreUnknown = true)
public class SearchQuizResult {
    private Long id;
    private String title;
    private String status;
    private Integer duration_minutes;

    public Long getId() { return id; }
    public String getTitle() { return title; }
    public String getStatus() { return status; }
    public Integer getDurationMinutes() { return duration_minutes; }
}