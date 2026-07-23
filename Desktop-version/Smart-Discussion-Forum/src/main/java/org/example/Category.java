package org.example;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import com.fasterxml.jackson.annotation.JsonProperty;

@JsonIgnoreProperties(ignoreUnknown = true)
public class Category {
    @JsonProperty("CategoryID")
    private Long CategoryID;
    @JsonProperty("CategoryName")
    private String CategoryName;

    public Long getCategoryId() { return CategoryID; }
    public String getCategoryName() { return CategoryName; }

    @Override
    public String toString() {
        return CategoryName;
    }
}