package com.aecor.eurosports.gson;

import com.google.gson.Gson;
import com.google.gson.GsonBuilder;

import java.lang.reflect.Type;

public class GsonConverter {
    private static GsonConverter gsonConverter = null;
    protected Gson gson;

    public GsonConverter() {
        gson = new GsonBuilder().create();
    }

    public static GsonConverter getInstance() {
        if (gsonConverter == null) {
            gsonConverter = new GsonConverter();
        }
        return gsonConverter;
    }

    public <T> T decodeFromJsonString(String jsonString, Class<T> targetClass) {
        return gson.fromJson(jsonString, targetClass);
    }

    public <T> T decodeFromJsonString(String jsonString, Type targetType) {
        return gson.fromJson(jsonString, targetType);
    }

    public String encodeToJsonString(Object src) {
        return gson.toJson(src);
    }

}
