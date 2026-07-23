package org.example;

import javafx.scene.Node;
import javafx.scene.control.Label;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;

import java.util.HashMap;
import java.util.Map;

public class EmojiImages {

    private static final Map<String, Image> cache = new HashMap<>();
    
    private static final String[] KNOWN_EMOJIS = {
    "😀","😃","😄","😁","😆","😂","🤣","😊","😍","😘",
    "🙂","😉","😎","🤔","😐","👍","❤️","🤍","😢","😮",
    "😡","⚠️","📎"
};




    // Converts an emoji string (e.g. "😀" or "❤️") to its Twemoji filename (e.g. "1f600" or "2764")
    private static String toFilename(String emoji) {
        StringBuilder sb = new StringBuilder();
        int i = 0;
        while (i < emoji.length()) {
            int codePoint = emoji.codePointAt(i);
            i += Character.charCount(codePoint);
            if (codePoint == 0xFE0F) continue; // strip the variation selector — Twemoji filenames omit it
            if (sb.length() > 0) sb.append('-');
            sb.append(Integer.toHexString(codePoint));
        }
        return sb.toString();
    }

    private static Image get(String emoji) {
        String filename = toFilename(emoji);
        return cache.computeIfAbsent(filename, f -> {
            var stream = EmojiImages.class.getResourceAsStream("/emojis/" + f + ".png");
            if (stream == null) {
                System.err.println("Missing emoji image: " + f + ".png (for " + emoji + ")");
                return null;
            }
            return new Image(stream);
        });
    }

    // Returns an ImageView at the given size, or falls back to a plain text Label
    // if that emoji's PNG wasn't downloaded — so a missing file never crashes the app
    public static Node buildNode(String emoji, double size) {
        Image img = get(emoji);
        if (img != null) {
            ImageView iv = new ImageView(img);
            iv.setFitWidth(size);
            iv.setFitHeight(size);
            iv.setPreserveRatio(true);
            return iv;
        }
        return new Label(emoji);
    }

     private static final String[] KNOWN_EMOJIS_SORTED =
    java.util.Arrays.stream(KNOWN_EMOJIS)
        .sorted((a, b) -> b.length() - a.length())
        .toArray(String[]::new);


     public static javafx.scene.text.TextFlow buildRichText(String message, double emojiSize) {
    javafx.scene.text.TextFlow flow = new javafx.scene.text.TextFlow();
    StringBuilder plainBuffer = new StringBuilder();
    int i = 0;

    while (i < message.length()) {
        String matched = matchEmojiAt(message, i);
        if (matched != null) {
            if (plainBuffer.length() > 0) {
                flow.getChildren().add(new javafx.scene.text.Text(plainBuffer.toString()));
                plainBuffer.setLength(0);
            }
            flow.getChildren().add(buildNode(matched, emojiSize));
            i += matched.length();
        } else {
            int codePoint = message.codePointAt(i);
            plainBuffer.appendCodePoint(codePoint);
            i += Character.charCount(codePoint);
        }
    }

    if (plainBuffer.length() > 0) {
        flow.getChildren().add(new javafx.scene.text.Text(plainBuffer.toString()));
    }

    return flow;
}

private static String matchEmojiAt(String text, int index) {
    for (String emoji : KNOWN_EMOJIS_SORTED) {
        if (text.regionMatches(index, emoji, 0, emoji.length())) {
            return emoji;
        }
    }
    return null;
}   

}
