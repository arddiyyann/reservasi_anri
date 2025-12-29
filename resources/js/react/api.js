import axios from "axios";

export function authHeaders() {
    const token = localStorage.getItem("token");
    return {
        Accept: "application/json",
        ...(token ? { Authorization: `Bearer ${token}` } : {}),
    };
}

export const api = axios.create({
    baseURL: "/", // same domain
});
