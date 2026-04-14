window.App = window.App || {};

App.mapManager = {

    map: null,
    marker: L.marker,
    mapId: null,
    defaultCenter: [-12.0464, -77.0428],
    defaultZoom: 13,
    initialLat: null,
    initialLng: null,
    inputAddressId: null,

    init(mapId, inputAddressId, options = {}) {
        this.mapId = mapId;
        this.inputAddressId = inputAddressId;

        if (options.center) this.defaultCenter = options.center;
        if (options.zoom) this.defaultZoom = options.zoom;

        this.initialLat = options.lat || null;
        this.initialLng = options.lng || null;

        let center = this.defaultCenter;

        if (this.initialLat && this.initialLng) {
            center = [this.initialLat, this.initialLng];
        }

        this.map = L.map(this.mapId).setView(center, this.defaultZoom);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(this.map);

        if (this.initialLat && this.initialLng && options.address) {
            this.setMarker(this.initialLat, this.initialLng, options.address);
            this.setInputAddressSearch(options.address);
        } else {
            this.setMarker(-12.0464, -77.0428, "Centro de Lima");
        }

        return this;
    },

    setInputAddressSearch(address) {
        const addrInput = document.getElementById('address-search');
        if( addrInput ) {
            addrInput.value = address;
        }
    },

    setMarker(lat, lng, addr = "Ubicación") {
        console.log('setMarker');

        if (this.marker) {
            this.map.removeLayer(this.marker);
        }

        this.marker = L.marker([lat, lng], {
            draggable: true
        }).addTo(this.map)
          .bindPopup(addr)
          .openPopup();

        this.marker.label = addr;

        this.marker.on('dragend', () => {
            const pos = this.marker.getLatLng();
            this.reverseGeocode(pos.lat, pos.lng);
        });

        this.updateInputs(lat, lng, addr);
    },

    searchAddress() {
        let address = document.getElementById(this.inputAddressId).value;

        if (!address) return;

        const url = "https://nominatim.openstreetmap.org/search?format=json&q=" + encodeURIComponent(address);

        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (!data.length) {
                    alert("Dirección no encontrado");
                    return;
                }

                const lat = parseFloat(data[0].lat);
                const lng = parseFloat(data[0].lon);
                const label = data[0].display_name;

                this.map.setView([lat, lng], 16);
                this.setMarker(lat, lng, label);
            });
    },

    reverseGeocode(lat, lng) {
        console.log('reverseGeocode', lat, lng);
        const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                console.log(data);
                if (data && data.display_name) {
                    const label = data.display_name;
                    this.updateInputs(data.lat, data.lon, label);
                    this.marker.label = label;
                    this.marker.setTooltipContent(label);
                    this.marker.setPopupContent(label);
                    this.marker.openPopup();
                }
            });
    },

    updateInputs(lat, lng, addr) {
        console.log('updateInputs', lat, lng, addr);

        const latInput = document.getElementById('latitud');
        const lngInput = document.getElementById('longitud');
        const addrInput = document.getElementById('ubicacion');

        console.log(
            latInput.value,
            lngInput.value,
            addrInput.value
        )

        if (latInput && lngInput && addrInput) {
            latInput.value = lat;
            lngInput.value = lng;
            addrInput.value = addr;
        }
    },

    invalidateSize() {
        if (this.map) {
            this.map.invalidateSize();
            if( this.marker ) {
                this.marker.openPopup();
            }
        }
    }
};