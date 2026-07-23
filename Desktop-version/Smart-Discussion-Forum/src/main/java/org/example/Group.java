package org.example;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import com.fasterxml.jackson.annotation.JsonProperty;

@JsonIgnoreProperties(ignoreUnknown = true)
public class Group {
    private Long id;
    private String name;
    private String description;
    private String visibility;
    private Long created_by;

    @JsonProperty("members_count")
    private Integer members_count;

    private Creator creator;

    public Long getId() { return id; }
    public String getName() { return name; }
    public String getDescription() { return description; }
    public String getVisibility() { return visibility; }
    public Long getCreatedBy() { return created_by; }
    public Integer getMembersCount() { return members_count; }
    public Creator getCreator() { return creator; }

    @JsonIgnoreProperties(ignoreUnknown = true)
    public static class Creator {
        private Long id;
        private String name;
        public Long getId() { return id; }
        public String getName() { return name; }
    }
}