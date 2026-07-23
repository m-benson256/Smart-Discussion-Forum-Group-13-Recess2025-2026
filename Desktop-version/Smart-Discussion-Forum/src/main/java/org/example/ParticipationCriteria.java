package org.example;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import com.fasterxml.jackson.annotation.JsonProperty;

@JsonIgnoreProperties(ignoreUnknown = true)
public class ParticipationCriteria {
    private Long id;
    private Long lecturer_id;
    @JsonProperty("points_per_message")
    private Integer points_per_message;
    @JsonProperty("points_per_reaction_given")
    private Integer points_per_reaction_given;
    @JsonProperty("max_score")
    private Integer max_score;

    public Integer getPointsPerMessage() { return points_per_message; }
    public Integer getPointsPerReactionGiven() { return points_per_reaction_given; }
    public Integer getMaxScore() { return max_score; }
}