const aclLookup = {
  '': {
    0: false,
    1: true
  },
  'm120': {
    1: 'View/Edit',
    2: 'View Only'
  },
  'm180': {
    0: 'No Access',
    1: 'Full Access',
    2: 'Print Only'
  }
}

export default {
  computed: {
    hasRole() {
      return (role, user) => {
        role = Array.isArray(role) ? role : [role]

        return user.roles.map(({name}) => name).filter((key) => role.includes(key)).length > 0
      }
    },
    permissions() {
      return (accessor, user) => {
        if (this.hasRole(['admin', 'owner', 'consultant'], user)) {
          switch (accessor) {
            case 'm120':
                return 'View/Edit'
            case 'm180':
                return 'Full Access'
            default:
                return true
          }
        }

        const permission = user.acl[accessor]

        if (!permission) {
          return false
        }

        return (aclLookup[accessor] || aclLookup[''])[permission]
      }
    }
  }
}