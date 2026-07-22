package org.example;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import com.fasterxml.jackson.annotation.JsonProperty;

@JsonIgnoreProperties(ignoreUnknown = true)
public class Topic {
    private Long id;
    private String title;
    private String content;
    @JsonProperty("messages_count")
    private Integer messages_count;
    private TopicUser user;

    public Long getId() { return id; }
    public String getTitle() { return title; }
    public String getContent() { return content; }
    public Integer getMessagesCount() { return messages_count; }
    public TopicUser getUser() { return user; }

    @JsonIgnoreProperties(ignoreUnknown = true)
    public static class TopicUser {
        private Long id;
        private String name;
        public String getName() { return name; }
    }
}