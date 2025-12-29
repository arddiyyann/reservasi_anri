import http from "./http";

export async function getServices() {
    const { data } = await http.get("/services");
    return data;
}

export async function getServiceSlots(serviceId) {
    const { data } = await http.get(`/services/${serviceId}/slots`);
    return data;
}
