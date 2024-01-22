import { usePage } from "@inertiajs/vue3";

export function usePermission(props) {
    console.log( { ...usePage() })
    const hasRole = (name) => usePage().props.auth.user.roles.includes(name);
    const hasPermission =  (name) => usePage().props.auth.user.permissions.includes(name);

    return { hasRole, hasPermission }
}