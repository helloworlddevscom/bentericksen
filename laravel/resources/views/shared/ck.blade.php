var ck_info = {
    user: {
        id: "{{ Auth::user()->id }}",
        name: "{{ Auth::user()->full_name }}",
        type: "{{ Auth::user()->canApprove() ? 'master' : 'editor' }}"
    },
    enableLite: true,
    buttonVS: {{ (Auth()->user()->business_id == 0 || Auth()->user()->getRole() == 'admin') ? 'true' : 'false' }}
};
