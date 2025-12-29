import { useEffect, useState } from "react";
import { getServices } from "../api/services";
import { Link } from "react-router-dom";

export default function Services() {
    const [items, setItems] = useState([]);
    const [err, setErr] = useState("");

    useEffect(() => {
        (async () => {
            try {
                const data = await getServices();
                // asumsi response { data: [...] }
                setItems(data.data || data);
            } catch (e) {
                setErr(e?.response?.data?.message || "Gagal load services");
            }
        })();
    }, []);

    return (
        <div style={{ maxWidth: 800, margin: "40px auto" }}>
            <h2>Services</h2>
            {err && <p style={{ color: "red" }}>{err}</p>}
            <ul>
                {items.map((s) => (
                    <li key={s.id}>
                        <Link to={`/services/${s.id}`}>{s.name}</Link>
                    </li>
                ))}
            </ul>
        </div>
    );
}
