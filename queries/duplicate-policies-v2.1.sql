select b.id, b.name, p.manual_name, COUNT(*)
from policies p inner join business b on p.business_id = b.id
where (p.special is null OR p.special = '') and p.status <> 'closed' and p.is_custom = 0 and p.deleted_at is null
group by b.id, b.name, p.manual_name
having COUNT(*) > 1