import _ from 'lodash'

export const vDetail = state => {
  return state.vehicleDetails.map(vehicle => {
    return {
      label: vehicle.data.registration_number,
      value: vehicle.data.registration_number
    }
  })
}
