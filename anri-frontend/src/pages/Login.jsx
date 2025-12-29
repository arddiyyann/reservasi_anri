import { useState } from "react";
import { login } from "../api/auth";
import { useNavigate } from "react-router-dom";

export default function Login() {
    const nav = useNavigate();
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [err, setErr] = useState("");

    async function onSubmit(e) {
        e.preventDefault();
        setErr("");
        try {
            const data = await login(email, password);

            // Sesuaikan dengan response backend kamu:
            // misal token ada di data.token
            if (data.token) localStorage.setItem("token", data.token);

            // optional: simpan user
            if (data.user) localStorage.setItem("user", JSON.stringify(data.user));

            nav("/services");
        } catch (e2) {
            setErr(e2?.response?.data?.message || "Login gagal");
        }
    }

    return (
        <div style={{ maxWidth: 420, margin: "40px auto" }}>
            <h2>Login</h2>
            <form onSubmit={onSubmit}>
                <div>
                    <label>Email</label>
                    <input value={email} onChange={(e) => setEmail(e.target.value)} />
                </div>
                <div style={{ marginTop: 10 }}>
                    <label>Password</label>
                    <input
                        type="password"
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                    />
                </div>
                {err && <p style={{ color: "red" }}>{err}</p>}
                <button style={{ marginTop: 12 }}>Masuk</button>
            </form>
        </div>
    );
}
