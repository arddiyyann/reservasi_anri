import React, { useState } from "react";
import axios from "axios";

export default function App() {
    const [email, setEmail] = useState("admin@anri.test");
    const [password, setPassword] = useState("admin12345");
    const [error, setError] = useState("");
    const [loading, setLoading] = useState(false);

    async function login(e) {
        e.preventDefault();
        setError("");
        setLoading(true);

        try {
            const res = await axios.post(
                "/api/login",
                { email, password },
                { headers: { Accept: "application/json" } }
            );

            localStorage.setItem("token", res.data.token);

            // untuk sekarang: redirect ke / (nanti jadi dashboard)
            window.location.href = "/";
        } catch (err) {
            setError(err?.response?.data?.message || "Login gagal");
        } finally {
            setLoading(false);
        }
    }

    return (
        <div className="page">
            <div className="card">
                <div className="brand">
                    <div className="logo" />
                    <div>
                        <p className="h1">Reservasi ANRI</p>
                        <p className="p">Login untuk masuk ke sistem reservasi.</p>
                    </div>
                </div>

                <form onSubmit={login}>
                    <div className="label">Email</div>
                    <input
                        className="input"
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                        placeholder="email"
                    />

                    <div className="label">Password</div>
                    <input
                        className="input"
                        type="password"
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                        placeholder="password"
                    />

                    <div className="row">
                        <span className="badge">API Token (Bearer)</span>
                    </div>

                    <div style={{ marginTop: 12 }}>
                        <button className="btn" disabled={loading}>
                            {loading ? "Signing in..." : "Sign in"}
                        </button>
                    </div>

                    {error ? <div className="error">{error}</div> : null}

                    <div className="hint">
                        Akun dev admin: <b>admin@anri.test</b> / <b>admin12345</b><br />
                        (Nanti kita buat halaman register & role-based menu.)
                    </div>
                </form>
            </div>
        </div>
    );
}
