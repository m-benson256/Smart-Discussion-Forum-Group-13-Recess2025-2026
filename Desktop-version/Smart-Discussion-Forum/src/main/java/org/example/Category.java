package org.example;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;

@JsonIgnoreProperties(ignoreUnknown = true)
public class Category {
    private Long CategoryID;
    private String CategoryName;

    public Long getCategoryId() { return CategoryID; }
    public String getCategoryName() { return CategoryName; }

    @Override
    public String toString() {
        return CategoryName; // so it displays nicely in the ComboBox
    }
}