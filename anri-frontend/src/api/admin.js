import http from "./http";

export async function adminReservations() {
    const { data } = await http.get("/admin/reservations");
    return data;
}

export async function approveReservation(id) {
    const { data } = await http.post(`/admin/reservations/${id}/approve`);
    return data;
}

export async function rejectReservation(id) {
    const { data } = await http.post(`/admin/reservations/${id}/reject`);
    return data;
}
