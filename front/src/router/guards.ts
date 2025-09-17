import type { RouteLocationNormalized, NavigationGuardNext } from 'vue-router'
/**
 * Router guard helpers that safely access stores
 * These functions ensure stores are available when called
 */
/**
 * Guard for validating neighborhood name and setting marker
 */
export async function validateOrganizationId(
  to: RouteLocationNormalized,
  from: RouteLocationNormalized,
  next: NavigationGuardNext
) {

  try 
  {
    const { useOrganizationsStore } = await import('@/stores/organizationsStore')
    const orgsStore = useOrganizationsStore()
    const id = Number(to.params.id as string) as number
    if (!id) {
        console.error('No organization id provided')
        return next({ name: 'NotFound' })
    }
    const match = await orgsStore.fetchOrganizationById(id as number)

    if (!match) {
      console.warn(`Organization not found: ${id}`)
      return next({ name: 'NotFound' })
    }
    // Continue to route
    next()
  } catch (error) 
  {
    console.error('Error in neighborhood validation guard:', error)
    next({ name: 'NotFound' })
  }
}
