import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

export default function usePermissions() {
    const page = usePage();

    // Ambil role dan permissions dari props auth
    const permissions = computed(() => page.props.auth?.permissions || []);
    const role = computed(() => page.props.auth?.role || "");

    // Cek satu permission
    const can = (permission) => {
        return permissions.value.includes(permission);
    };

    // Cek salah satu dari list permission
    const canAny = (...list) => {
        return list.some((p) => permissions.value.includes(p));
    };

    // ✅ Cek jika user memiliki role tertentu
    const hasRole = (targetRole) => {
        return role.value === targetRole;
    };

    // ✅ Cek jika user memiliki salah satu dari beberapa role
    const hasAnyRole = (...roles) => {
        return roles.includes(role.value);
    };

    return {
        permissions,
        role,
        can,
        canAny,
        hasRole,
        hasAnyRole,
    };
}
