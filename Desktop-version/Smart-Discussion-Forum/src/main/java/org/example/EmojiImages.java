package org.example;

import javafx.scene.Node;
import javafx.scene.control.Label;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;

import java.util.HashMap;
import java.util.Map;

public class EmojiImages {

    private static final Map<String, Image> cache = new HashMap<>();

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
}
