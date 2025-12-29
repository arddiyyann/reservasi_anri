import { useState } from "react";
import { staffMySlots } from "../api/staff";

export default function StaffMySlots() {
    const [date, setDate] = useState("");
    const [items, setItems] = useState([]);
    const [err, setErr] = useState("");

    async function load() {
        setErr("");
        try {
            const data = await staffMySlots(date);
            setItems(data.data || data);
        } catch (e) {
            setErr(e?.response?.data?.message || "Gagal load slot staff");
        }
    }

    return (
        <div style={{ maxWidth: 900, margin: "40px auto" }}>
            <h2>Slot Saya (Staff)</h2>
            <div style={{ display: "flex", gap: 8, alignItems: "center" }}>
                <input
                    type="date"
                    value={date}
                    onChange={(e) => setDate(e.target.value)}
                />
                <button onClick={load} disabled={!date}>
                    Cari
                </button>
            </div>

            {err && <p style={{ color: "red" }}>{err}</p>}
            <ul>
                {items.map((sl) => (
                    <li key={sl.id}>
                        {sl.date} {sl.start_time}-{sl.end_time} — {sl.service?.name}
                    </li>
                ))}
            </ul>
        </div>
    );
}
