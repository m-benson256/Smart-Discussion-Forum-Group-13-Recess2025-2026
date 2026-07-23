package org.example;

import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.net.http.HttpRequest;
import java.nio.charset.StandardCharsets;
import java.nio.file.Files;
import java.nio.file.Path;
import java.util.ArrayList;
import java.util.List;
import java.util.UUID;


public class MultipartBodyBuilder {

    private final String boundary = "----JavaFXBoundary" + UUID.randomUUID();
    private final List<byte[]> parts = new ArrayList<>();

    public String getBoundary() {
        return boundary;
    }

    public MultipartBodyBuilder addFile(String fieldName, Path file) throws IOException {
        String mimeType = Files.probeContentType(file);
        if (mimeType == null) mimeType = "application/octet-stream";

        String header = "--" + boundary + "\r\n"
            + "Content-Disposition: form-data; name=\"" + fieldName + "\"; filename=\"" + file.getFileName() + "\"\r\n"
            + "Content-Type: " + mimeType + "\r\n\r\n";

        ByteArrayOutputStream out = new ByteArrayOutputStream();
        out.write(header.getBytes(StandardCharsets.UTF_8));
        out.write(Files.readAllBytes(file));
        out.write("\r\n".getBytes(StandardCharsets.UTF_8));

        parts.add(out.toByteArray());
        return this;
    }

    public HttpRequest.BodyPublisher build() throws IOException {
        ByteArrayOutputStream out = new ByteArrayOutputStream();
        for (byte[] part : parts) out.write(part);
        out.write(("--" + boundary + "--\r\n").getBytes(StandardCharsets.UTF_8));
        return HttpRequest.BodyPublishers.ofByteArray(out.toByteArray());
    }
}
