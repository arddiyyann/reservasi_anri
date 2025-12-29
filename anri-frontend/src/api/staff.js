import http from "./http";

export async function staffMySlots(date) {
    const { data } = await http.get("/staff/my-slots", { params: { date } });
    return data;
}
