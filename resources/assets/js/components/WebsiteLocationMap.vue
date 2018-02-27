<template>
  <div>
    <div id="gmap_place_input">      
      <gmap-place-input :select-first-on-enter="true" @place_changed="updatePlace($event)"></gmap-place-input>
    </div>
    <gmap-map :center="center" :zoom="zoom" class="map-panel" @rightclick="mapRclicked" style="width: 100%; height: 600px">
      <gmap-cluster :grid-size="gridSize">
        <gmap-marker :key="index" v-for="(m, index) in activeMarkers" :position="m.position" :clickable="true" :draggable="true"  @click="toggleInfoWindow(m,index)" :zIndex="zIndex" @position_changed="updateChild(m, 'position', $event)"></gmap-marker>
      </gmap-cluster>
      <gmap-info-window :options="infoOptions" :position="infoWindowPos" :opened="infoWinOpen" @closeclick="infoWinOpen=false">
        <div class="form-group mb-0">
          <textarea v-model="infoContent" class="form-control mb-2" name="infoWindowText" id="infoWindowText" rows="5" v-validate="{'required':true}"></textarea>
          <i v-show="errors.has('infoWindowText')" class="fa fa-warning"></i>
          <span class="help is-danger" v-show="errors.has('infoWindowText')">{{ errors.first('infoWindowText') }}<br>
          </span>
          <button type="button" class="btn btn-sm btn-primary mt-2" @click="deleteMarker()">Delete</button>
          <button type="button" class="btn btn-sm btn-primary mt-2" @click="saveMarker()">Save</button>
        </div>
      </gmap-info-window>
    </gmap-map>
  </div>  
</template>
<style>
.app-panel {
  width: 100%;
  height: 100%;
  position: fixed;
  top: 0;
  left: 0;
  display: flex;
  flex-direction: row;
}
.map-panel {
  flex: 4 1 80%;
}
.settings-panel {
  overflow-y: scroll;
  flex: 1 0 500px;
}
gmap-map {
  width: 100%;
  height: 600px;
  display: block;
}
</style>
<script>
import Website from '../api/website.js';
import { ErrorBag } from 'vee-validate';
import * as VueGoogleMaps from 'vue2-google-maps';
import Vue from 'vue';
import _ from 'lodash';

Vue.use(VueGoogleMaps, {
  load: {
    key: 'AIzaSyCUkAy2II7BJnOURSZWB9_3FyuSgBnq2Lc',
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
      currentMidx: null,
      //optional: offset infowindow so it visually sits nicely on top of our marker
      infoOptions: {
        pixelOffset: {
          width: 0,
          height: -35
        }
      },
      zIndex: 9999,
      lastId: 1,
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
      gridSize: 50,
      mapType: 'terrain',
      markers: [],
      markersEven: false,
      drag: 0,
      mapClickedCount: 0,
      ifw: false,
      ifw2: false,
      ifw2text: 'You can also use the content prop to set your modal',
      mapStyle: 'green',
      circleBounds: {},
      displayCircle: false,
      displayRectangle: false,
      rectangleBounds: {
        north: 33.685,
        south: 50.671,
        east: -70.234,
        west: -116.251
      },
      originalPlPath: [
        {
          lat: 37.772,
          lng: -122.214
        }, {
          lat: 21.291,
          lng: -157.821
        }, {
          lat: -18.142,
          lng: 178.431
        }, {
          lat: -27.467,
          lng: 153.027
        }
      ],
      plPath: [
        {
          lat: 37.772,
          lng: -122.214
        }, {
          lat: 21.291,
          lng: -157.821
        }, {
          lat: -18.142,
          lng: 178.431
        }, {
          lat: -27.467,
          lng: 153.027
        }
      ],
      pleditable: true,
      plvisible: false,
      pgvisible: false,
      pgPath: [
        [{
          lat: 38.872886,
          lng: -77.054720
        }, {
          lat: 38.872602,
          lng: -77.058046
        }, {
          lat: 38.870080,
          lng: -77.058604
        }, {
          lat: 38.868894,
          lng: -77.055664
        }, {
          lat: 38.870598,
          lng: -77.053346
        }],
        [{
          lat: 38.871684,
          lng: -77.056780
        }, {
          lat: 38.871867,
          lng: -77.055449
        }, {
          lat: 38.870915,
          lng: -77.054891
        }, {
          lat: 38.870113,
          lng: -77.055836
        }, {
          lat: 38.870581,
          lng: -77.057037
        }]
      ],
      opgPath: [
        [{
          lat: 38.872886,
          lng: -77.054720
        }, {
          lat: 38.872602,
          lng: -77.058046
        }, {
          lat: 38.870080,
          lng: -77.058604
        }, {
          lat: 38.868894,
          lng: -77.055664
        }, {
          lat: 38.870598,
          lng: -77.053346
        }],
        [{
          lat: 38.871684,
          lng: -77.056780
        }, {
          lat: 38.871867,
          lng: -77.055449
        }, {
          lat: 38.870915,
          lng: -77.054891
        }, {
          lat: 38.870113,
          lng: -77.055836
        }, {
          lat: 38.870581,
          lng: -77.057037
        }]
      ],
      scrollwheel: true,
      currentMarkerIndex: -1,
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
    mapStyles() {
      switch (this.mapStyle) {
      case 'normal':
        return [];
      case 'red':
        return [{
          stylers: [{
            hue: '#890000'
          }, {
            visibility: 'simplified'
          }, {
            gamma: 0.5
          }, {
            weight: 0.5
          }]
        }, {
          elementType: 'labels',
          stylers: [{
            visibility: 'off'
          }]
        }, {
          featureType: 'water',
          stylers: [{
            color: '#890000'
          }]
        }];
      default:
        return [{
          stylers: [{
            hue: '#899999'
          }, {
            visibility: 'on'
          }, {
            gamma: 0.5
          }, {
            weight: 0.5
          }]
        }, {
          featureType: 'road',
          stylers: [{
            visibility: 'off'
          }]
        }, {
          featureType: 'transit.line',
          stylers: [{
            color: '#FF0000'
          }]
        }, {
          featureType: 'poi',
          elementType: 'labels.icon',
          stylers: [{
            visibility: 'on'
          }, {
            weight: 10
          }]
        }, {
          featureType: 'water',
          stylers: [{
            color: '#8900FF'
          }, {
            weight: 9999900000
          }, ]
        }];
      }
    }
  },
  mounted() {
    this.getAllMarkers();
    this.$root.$on('getMarkers', this.getMarkers);
  },
  methods: {
    getAllMarkers() {
      var vm = this;
      Website.getMarkers(this.getWebsite).then(
        (response) => {
          vm.markers = response.data.data;
          vm.markers = _.map(response.data.data, function(marker) {
            marker.position = { 'lat' : parseFloat(marker.latitude), 'lng' : parseFloat(marker.longitude)  };
            return marker;
          });
        },
        (error) => {
        }
      );
    },
    mapRclicked(mouseArgs) {
      const createdMarker = this.addMarker();
      createdMarker.position.lat = mouseArgs.latLng.lat();
      createdMarker.position.lng = mouseArgs.latLng.lng();
    },
    addMarker() {
      this.lastId++;
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
      this.toggleInfoWindow(this.markers[this.markers.length - 1], this.markers.length - 1);
      return this.markers[this.markers.length - 1];
    },
    updateMapCenter(which, value) { // eslint-disable-line no-unused-vars
      this.center = _.clone(this.reportedCenter);
    },
    mapClicked(mouseArgs) {
      console.log('map clicked', mouseArgs); // eslint-disable-line no-console
    },
    resetPlPath() {
      this.plPath = this.originalPlPath;
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
    updatePolygonPaths(paths) { //eslint-disable-line no-unused-vars
      // TODO
    },
    updatePolylinePath(paths) { //eslint-disable-line no-unused-vars
      // TODO:
    },
    updateCircle(prop, value) {
      if (prop === 'radius') {
        this.radius = value;
      } else if (prop === 'bounds') {
        this.circleBounds = value;
      }
    },
    updateRectangle(prop, value) {
      if (prop === 'bounds') {
        this.rectangleBounds = value;
      }
    },
    updatePlace(place) {
      if (place && place.geometry && place.geometry.location) {
        var marker = this.addMarker();
        marker.position.lat = place.geometry.location.lat();
        marker.position.lng = place.geometry.location.lng();
      }
    },
    toggleInfoWindow: function(marker, idx) {
      this.currentMarkerIndex = idx;
      this.infoWindowPos = marker.position;
      this.infoContent = marker.information;
      //check if its the same marker that was selected if yes toggle
      if (this.currentMidx == idx) {
        this.infoWinOpen = !this.infoWinOpen;
      }
      //if different marker set infowindow to open and reset current marker index
      else {
        this.infoWinOpen = true;
        this.currentMidx = idx;
      }
    },
    saveMarker() {
      this.$validator.validateAll().then((response) => {
        if(response) {
          this.markers[this.currentMarkerIndex].information = this.infoContent;
          this.toggleInfoWindow(this.markers[this.currentMarkerIndex], this.currentMarkerIndex)
        }
      }).catch(() => {
        // fail stuff
      });
    },
    deleteMarker() {
      this.toggleInfoWindow(this.markers[this.currentMarkerIndex], this.currentMarkerIndex);      
      this.markers.splice(this.currentMarkerIndex, 1);
    },
    getMarkers() {
      this.$emit('setMarkers', this.markers);      
    },
  }
}
</script>