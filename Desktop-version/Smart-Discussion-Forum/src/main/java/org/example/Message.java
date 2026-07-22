package org.example;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;

@JsonIgnoreProperties(ignoreUnknown = true)
public class Message {
    private Long id;
    private String body;
    private MessageUser user;

    public Long getId() { return id; }
    public String getBody() { return body; }
    public MessageUser getUser() { return user; }

    @JsonIgnoreProperties(ignoreUnknown = true)
    public static class MessageUser {
        private Long id;
        private String name;
        public String getName() { return name; }
    }
}