import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import { getServiceSlots } from "../api/services";
import { createReservation } from "../api/reservations";

export default function ServiceSlots() {
    const { id } = useParams();
    const [slots, setSlots] = useState([]);
    const [err, setErr] = useState("");
    const [msg, setMsg] = useState("");

    async function load() {
        setErr("");
        const data = await getServiceSlots(id);
        setSlots(data.data || data);
    }

    useEffect(() => {
        load().catch((e) => setErr(e?.response?.data?.message || "Gagal load slots"));
    }, [id]);

    async function book(slotId) {
        setErr("");
        setMsg("");
        try {
            // sesuaikan payload sesuai ReservationController kamu
            await createReservation({ service_slot_id: slotId });
            setMsg("Reservasi berhasil dibuat.");
            await load();
        } catch (e) {
            setErr(e?.response?.data?.message || "Gagal reservasi");
        }
    }

    return (
        <div style={{ maxWidth: 900, margin: "40px auto" }}>
            <h2>Slots</h2>
            {msg && <p style={{ color: "green" }}>{msg}</p>}
            {err && <p style={{ color: "red" }}>{err}</p>}
            <table border="1" cellPadding="8">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {slots.map((sl) => (
                        <tr key={sl.id}>
                            <td>{sl.date}</td>
                            <td>{sl.start_time}</td>
                            <td>{sl.end_time}</td>
                            <td>{sl.capacity}</td>
                            <td>{sl.is_closed ? "Closed" : "Open"}</td>
                            <td>
                                <button disabled={sl.is_closed} onClick={() => book(sl.id)}>
                                    Reservasi
                                </button>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
}
