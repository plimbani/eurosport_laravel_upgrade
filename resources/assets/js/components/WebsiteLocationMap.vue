<template>
  <div>
    <div id="gmap_place_input">
      <gmap-place-input :placeholder="'Enter a location or right click on the map'" :select-first-on-enter="true" @place_changed="updatePlace($event)"></gmap-place-input>
    </div>
    <gmap-map :center="center" :zoom="zoom" class="map-panel" @rightclick="mapRclicked" @zoom_changed="update('zoom', $event)" @center_changed="update('reportedCenter', $event)" @maptypeid_changed="update('mapType', $event)" @bounds_changed="update('bounds', $event)" ref="venuemap" style="width: 100%; height: 400px; display: block;">
      <gmap-marker :key="index" v-for="(m, index) in activeMarkers" :position="m.position" :clickable="true" :draggable="true" @click="updateInfoWindow(m,index)" :zIndex="zIndex" @position_changed="updateChild(m, 'position', $event)"></gmap-marker>
      <gmap-info-window :options="infoOptions" :position="infoWindowPos" :opened="infoWinOpen" @closeclick="infoWinOpen=false">
        <div class="form-group mb-0">
          <textarea v-model="infoContent" class="form-control mb-2" name="infoWindowText" id="infoWindowText" rows="5" v-validate="{'required':true}" data-vv-as="information" placeholder="Marker information"></textarea>
          <i v-show="errors.has('infoWindowText')" class="fas fa-warning"></i>
          <span class="help is-danger" v-show="errors.has('infoWindowText')">{{ errors.first('infoWindowText') }}<br>
          </span>
          <button type="button" class="btn btn-sm btn-primary mt-2" @click="deleteMarker()">Delete</button>
          <button type="button" class="btn btn-sm btn-primary mt-2" @click="saveMarker()">Save</button>
        </div>
      </gmap-info-window>
    </gmap-map>
  </div>
</template>
<script>
import Website from '../api/website.js';
import { ErrorBag } from 'vee-validate';
import * as VueGoogleMaps from 'vue2-google-maps';
import Vue from 'vue';
import _ from 'lodash';

Vue.use(VueGoogleMaps, {
  load: {
    key: 'AIzaSyBa2EjSYfQCTOGXlxbkElOi56nGm_GA7Ko',
    libraries: 'places',
  }
});

export default {
  data() {
    return {
      infoContent: '',
      infoWindowPos: {
        lat: 0,
        lng: 0
      },
      infoWinOpen: false,
      //optional: offset infowindow so it visually sits nicely on top of our marker
      infoOptions: {
        pixelOffset: {
          width: 0,
          height: -35
        }
      },
      zIndex: 9999,
      center: {
        lat: 51.509865,
        lng: -0.118092
      },
      reportedCenter: {
        lat: 10.0,
        lng: 10.0
      },
      mapBounds: {},
      clustering: true,
      zoom: 8,
      gridSize: 10,
      mapType: 'terrain',
      markers: [],
      markersEven: false,
      drag: 0,
      ifw: false,
      mapStyle: 'green',
      scrollwheel: true,
      currentMarkerIndex: -1,
      currentSearchedMarker: 0,
    }
  },
  computed: {
    getWebsite() {
      return this.$store.state.Website.id;
    },
    activeMarkers() {
      if (this.markersEven) {
        return this.markers.filter(
          (v, k) => k % 2 == 0
        );
      } else {
        return this.markers;
      }
    },
  },
  mounted() {
    this.getAllMarkers();
    this.$root.$on('getMarkers', this.getMarkers);
  },
  beforeCreate: function() {
    // Remove custom event listener
    this.$root.$off('getMarkers');
  },
  methods: {
    getAllMarkers() {
      var vm = this;
      Website.getMarkers(this.getWebsite).then(
        (response) => {
          var bounds = new google.maps.LatLngBounds();
          vm.markers = response.data.data;
          vm.markers = _.map(response.data.data, function(marker) {
            marker.position = { 'lat' : parseFloat(marker.latitude), 'lng' : parseFloat(marker.longitude)  };
            bounds.extend(_.cloneDeep(marker.position));
            return marker;
          });
          if(vm.markers.length > 0) {
            vm.$refs.venuemap.fitBounds(bounds);
            vm.$refs.venuemap.panToBounds(bounds);
            // google.maps.event.trigger(vm.$refs.venuemap.$mapObject, 'resize')

            if(vm.markers.length == 1){
              vm.zoom = 15;
            }
          }
        },
        (error) => {
        }
      );
    },
    mapRclicked(mouseArgs) {
      const createdMarker = this.addMarker();
      createdMarker.position.lat = mouseArgs.latLng.lat();
      createdMarker.position.lng = mouseArgs.latLng.lng();
      this.updateInfoWindow(createdMarker, this.markers.length - 1);
    },
    addMarker() {
      this.clearErrorMsgs();
      this.markers.push({
        id: '',
        position: {
          lat: 48.8538302,
          lng: 2.2982161
        },
        opacity: 1,
        draggable: true,
        enabled: true,
        dragended: 0,
        ifw: true,
        information: '',
      });
      return this.markers[this.markers.length - 1];
    },
    update(field, event) {
      if (field === 'reportedCenter') {
        // N.B. It is dangerous to update this.center
        // Because the center reported by Google Maps is not exactly
        // the same as the center you pass it.
        // Instead we update this.center only when the input field is changed.

        this.reportedCenter = {
          lat: event.lat(),
          lng: event.lng(),
        };

        // If you wish to test the problem out for yourself, uncomment the following
        // and see how your browser begins to hang:
        // this.center = _.clone(this.reportedCenter)
      } else if (field === 'bounds') {
        this.mapBounds = event;
      } else {
        this.$set(this, field, event);
      }
    },
    updateChild(object, field, event) {
      if (field === 'position') {
        object.position = {
          lat: event.lat(),
          lng: event.lng(),
        };
      }
    },
    updatePlace(place) {
      if (place && place.geometry && place.geometry.location) {
        var marker = this.addMarker();
        marker.position.lat = place.geometry.location.lat();
        marker.position.lng = place.geometry.location.lng();
        this.updateInfoWindow(marker, this.markers.length - 1);
        this.currentSearchedMarker = this.currentMarkerIndex;
      }
    },
    updateInfoWindow: function(marker, index) {
      this.infoWindowPos = _.cloneDeep(marker.position);
      this.infoContent = marker.information;
      this.toggleInfoWindow(index);
    },
    toggleInfoWindow: function(index) {
      if (this.currentMarkerIndex == index) {
        this.infoWinOpen = !this.infoWinOpen;
      } else {
        this.infoWinOpen = true;
        this.currentMarkerIndex = index;
      }
    },
    saveMarker() {
      this.$validator.validateAll().then((response) => {
        if(response) {
          if(this.currentSearchedMarker == this.currentMarkerIndex) {
            $("#gmap_place_input input").val('');
          }
          this.markers[this.currentMarkerIndex].information = this.infoContent;
          this.toggleInfoWindow(this.currentMarkerIndex);
        }
      }).catch(() => {
        // fail stuff
      });
    },
    deleteMarker() {
      this.toggleInfoWindow(this.currentMarkerIndex);
      this.markers.splice(this.currentMarkerIndex, 1);
    },
    getMarkers() {
      this.$emit('setMarkers', this.markers);
    },
  }
}
</script>