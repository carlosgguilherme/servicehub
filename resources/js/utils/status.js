export function statusLabel(status) {
  const map = {
    open: 'Aberto',
    in_progress: 'Em solução',
    closed: 'Fechado',
  }
  return map[status] ?? status
}

export function statusPillClasses(status) {
  const map = {
    open: 'bg-green-100 text-green-800 ring-green-200',
    in_progress: 'bg-yellow-100 text-yellow-800 ring-yellow-200',
    closed: 'bg-gray-100 text-gray-800 ring-gray-200',
  }
  return map[status] ?? 'bg-gray-100 text-gray-800 ring-gray-200'
}
