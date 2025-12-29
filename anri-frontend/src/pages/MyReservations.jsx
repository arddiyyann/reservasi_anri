import { useEffect, useState } from "react";
import { myReservations } from "../api/reservations";

export default function MyReservations() {
    const [items, setItems] = useState([]);
    const [err, setErr] = useState("");

    useEffect(() => {
        (async () => {
            try {
                const data = await myReservations();
                setItems(data.data || data);
            } catch (e) {
                setErr(e?.response?.data?.message || "Gagal load reservasi");
            }
        })();
    }, []);

    return (
        <div style={{ maxWidth: 900, margin: "40px auto" }}>
            <h2>Reservasi Saya</h2>
            {err && <p style={{ color: "red" }}>{err}</p>}
            <ul>
                {items.map((r) => (
                    <li key={r.id}>
                        #{r.id} - {r.status || "pending"}
                    </li>
                ))}
            </ul>
        </div>
    );
}
