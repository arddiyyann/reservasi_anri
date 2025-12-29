import React, { useState } from "react";
import api from "../lib/api";

export default function LoginPage() {
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState("");

    const submit = async (e) => {
        e.preventDefault();
        setError("");
        setLoading(true);

        try {
            const res = await api.post("/api/login", { email, password });

            // asumsi response: { token: "..." } atau { data: { token: "..." } }
            const token = res.data?.token ?? res.data?.data?.token;
            if (!token) throw new Error("Token tidak ditemukan di response login.");

            localStorage.setItem("token", token);

            // optional: cek siapa user
            await api.get("/api/me");

            window.location.href = "/"; // ganti ke halaman utama kamu
        } catch (err) {
            const status = err?.response?.status;
            if (status === 422) setError("Validasi gagal. Cek email/password.");
            else if (status === 401) setError("Email atau password salah.");
            else setError("Login gagal. Coba lagi.");
        } finally {
            setLoading(false);
        }
    };

    return (
        <div style={{ minHeight: "100vh", display: "grid", placeItems: "center", padding: 16 }}>
            <div style={{ width: "100%", maxWidth: 420, border: "1px solid #eee", borderRadius: 12, padding: 20 }}>
                <h1 style={{ fontSize: 22, marginBottom: 16 }}>Login</h1>

                {error && (
                    <div style={{ background: "#fee", color: "#900", padding: 10, borderRadius: 8, marginBottom: 12 }}>
                        {error}
                    </div>
                )}

                <form onSubmit={submit} style={{ display: "grid", gap: 12 }}>
                    <div>
                        <label>Email</label>
                        <input value={email} onChange={(e) => setEmail(e.target.value)} type="email" required
                            style={{ width: "100%", padding: 10, borderRadius: 8, border: "1px solid #ddd" }} />
                    </div>

                    <div>
                        <label>Password</label>
                        <input value={password} onChange={(e) => setPassword(e.target.value)} type="password" required
                            style={{ width: "100%", padding: 10, borderRadius: 8, border: "1px solid #ddd" }} />
                    </div>

                    <button type="submit" disabled={loading}
                        style={{ padding: 12, borderRadius: 8, border: 0, background: "#111", color: "#fff" }}>
                        {loading ? "Memproses..." : "Masuk"}
                    </button>
                </form>
            </div>
        </div>
    );
}
