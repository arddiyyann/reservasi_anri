import http from "./http";

export async function login(email, password) {
    const { data } = await http.post("/login", { email, password });
    // asumsi response: { token: "..." , user: {...} } atau sejenis
    return data;
}

export async function register(payload) {
    const { data } = await http.post("/register", payload);
    return data;
}

export async function me() {
    const { data } = await http.get("/me");
    return data;
}

export async function logout() {
    const { data } = await http.post("/logout");
    return data;
}
