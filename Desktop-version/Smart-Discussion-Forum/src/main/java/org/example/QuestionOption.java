package org.example;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import com.fasterxml.jackson.annotation.JsonProperty;

@JsonIgnoreProperties(ignoreUnknown = true)
public class QuestionOption {
    @JsonProperty("option_key")
    private String option_key;
    @JsonProperty("option_text")
    private String option_text;

    public QuestionOption() {}
    public QuestionOption(String option_key, String option_text) {
        this.option_key = option_key;
        this.option_text = option_text;
    }

    public String getOptionKey() { return option_key; }
    public void setOptionKey(String option_key) { this.option_key = option_key; }
    public String getOptionText() { return option_text; }
    public void setOptionText(String option_text) { this.option_text = option_text; }
}