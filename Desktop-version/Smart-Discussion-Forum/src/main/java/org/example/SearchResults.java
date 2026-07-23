package org.example;

import java.util.List;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;

@JsonIgnoreProperties(ignoreUnknown = true)
public class SearchResults {
    private List<SearchQuizResult> quizzes;
    private List<SearchStudentResult> students;

    public List<SearchQuizResult> getQuizzes() { return quizzes; }
    public List<SearchStudentResult> getStudents() { return students; }
}