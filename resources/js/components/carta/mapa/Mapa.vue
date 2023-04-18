<template>
    <div id="mapContainer" class="basemap child-component"></div>
</template>
  
<script>
import mapboxgl from "mapbox-gl";
import MapboxGeocoder from '@mapbox/mapbox-gl-geocoder';
import '@mapbox/mapbox-gl-geocoder/dist/mapbox-gl-geocoder.css';
import * as bootstrap from 'bootstrap';

export default {
    name: "BaseMap",
    props: ['seleccion', 'send', 'agenciasFinales', 'direccionIncidente'],
    data() {
        return {
            accessToken: 'pk.eyJ1Ijoiam5pcmVsbGFtcG9saXRlY25pY3MiLCJhIjoiY2xlc2ZkN2tiMDRrYjNzbnpmcGh1eG1payJ9.BYMy0fBa_so0Iz-qJMwGkA',
            localizaciones: [],
            localizaciones1: [],
            localizaciones2: [],
            localizaciones3: [],
            localizaciones4: [],
            localizaciones5: [],
            localizaciones6: [],
            localizaciones7: [],
            seleccionAgencias: [],
            lat: 2.18215,
            lang: 41.4021,
            mapa: null,
            incidentGeocoder: [],
            seleccionFinal: false
        };
    },

    //WATCHERS
    watch: {
        //si se elimina la selección de agencias desde el padre, se vacia el array que las contiene aquí
        agenciasFinales: {
            immediate: true,
            handler(newVal, oldVal) {
                this.seleccionAgencias = newVal
            }
        },

        //Escucha si se ha enviado la confirmacion de agencias seleccionadas en el formulario padre para proceder con el renderizado del mapa
        send: {
            immediate: true,
            handler(newVal, oldVal) {


                if (newVal) {
                    this.localizaciones = [],
                        this.obtenerAgencias(this.seleccion);
                }
                else {

                    this.localizaciones1 = [],
                        this.localizaciones2 = [],
                        this.localizaciones3 = [],
                        this.localizaciones4 = [],
                        this.localizaciones5 = [],
                        this.localizaciones6 = [],
                        this.localizaciones7 = []
                }
            }
        },

        lat: function (newVal, oldVal) {
            let map = this.mapa
            if (this.lat == 0.0) {
                console.log('lat =' + this.lat)
            } else {
                map.setCenter([this.lat, this.lang]);
                this.lat = 0.0
            }
        }
    },

    //METODOS
    methods: {
        addImageMarker(map, nameImage, imageRoute) {
            map.loadImage(
                imageRoute,
                (error, image) => {
                    if (error) throw error;

                    // Add the image to the map style.
                    map.addImage(nameImage, image);
                })
        },

        addMarker(me, map, namePoint, nameAg, localizaciones, sizeMarker) {
            map.addSource(namePoint, {
                'type': 'geojson',
                'data': {
                    'type': 'FeatureCollection',
                    'features': me[localizaciones]
                }
            });

            // Imprimir imagen TRANSIT                
            map.addLayer({
                'id': nameAg,
                'type': 'symbol',
                'source': namePoint, // reference the data source

                'layout': {
                    'icon-image': nameAg, // reference the image
                    'icon-size': sizeMarker
                }
            });
        },

        setPopup(me, map, popup, name) {
            document.onload = (event) => {
                document.getElementById("mapContainer").innerHTML = "";
            };
            map.on('mouseover', name, (e) => {
                // Cambaia el estilo del cursor a pointer
                map.getCanvas().style.cursor = 'pointer';

                // Copiar las coordenadas del marcador
                const coordinates = e.features[0].geometry.coordinates.slice();
                const description = e.features[0].properties.description;

                // Ensure that if the map is zoomed out such that multiple copies of the feature are visible, the popup appears over the copy being pointed to.
                while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
                    coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
                }

                // Setea la popup segun toda la info extraida del marcador
                popup.setLngLat(coordinates).setHTML(description).addTo(map);

                if (name != 'incidencia') {

                    let btn = document.getElementById('botonPop');
                    btn.addEventListener('click', function () {
                        let idAg = btn.getAttribute('agencia-id')
                        let tipLoc = btn.getAttribute('tipo-loc')
                        let tipAgen = btn.getAttribute('tipo-agencia')
                        tipAgen = parseInt(tipAgen)
                        let encontrado = false
                        let counter = 0
                        let element

                        do {
                            let transform = true
                            element = me[tipLoc][counter]

                            if (element.properties.title.id == idAg) {
                                let nom = element.properties.title.nom
                                let idTipoAgencia = element.properties.title.agencies_primaries_id
                                // si ya existe una de este tipo agencia guardado no hace push y sale modal diciendo si quiere cambiar la seleccion if(me.seleccionAgencias) 
                                if (me.seleccionAgencias.length > 0) {
                                    me.seleccionAgencias.forEach(x => {
                                        if (x[2] == tipAgen) {
                                            me.$emit('cambiarSeleccion', element)
                                            transform = false
                                        } 
                                    });
                                    if (transform) {
                                        me.guardarSeleccion(me, idAg, nom, idTipoAgencia)
                                        me.$emit('getAgencia', me.seleccionAgencias)
                                    }
                                }
                                else {
                                    me.guardarSeleccion(me, idAg, nom, idTipoAgencia)
                                    me.$emit('getAgencia', me.seleccionAgencias)
                                    /*
                                    
                                    Emit para alert diciendo que se ha seleccionado correctamente
                                    
                                    */
                                }
                                encontrado = true
                            }
                            counter++
                        } while (!encontrado);
                    });
                }
            });
        },

        //Se obtienen de la BBDD la info de las agencias seleccionadas por tipo
        obtenerAgencias(arrayId) {

            if (arrayId.length > 1) {
                arrayId.forEach(id => {
                    axios
                        .get('agencies/' + id)
                        .then(response => {
                            const data = this.localizaciones

                            response.data.forEach(element => {
                                data.push(
                                    element
                                )
                            })
                            return new Promise(resolve => resolve());
                        })
                        .then(() => {
                            return this.marcasAgencias();
                        });
                });
            } else {
                axios
                    .get('agencies/' + arrayId)
                    .then(response => {
                        const data = this.localizaciones

                        response.data.forEach(element => {
                            data.push(
                                element
                            )
                        })

                        //promise para que no se ejecute el method marcasAgencias asincronamente
                        return new Promise(resolve => resolve());
                    })
                    .then(() => {
                        return this.marcasAgencias();
                    });
            }
        },

        //Envia las agencias seleccionadas en el mapa al padre AgenciasPrimarias.vue
        guardarSeleccion(me, idAgencia, nombre, tipo) {
            me.seleccionAgencias.push([parseInt(idAgencia), nombre, tipo])
        },

        //Obtiene las coordenadas del incidente pasado por un string y las guarda en las variables lat lang y centra el mapa en las coordenadas
        coordIncidente() {
            let me = this;

            function getMapData(me) {

                return new Promise((resolve, reject) => {
                    axios
                        .get(
                            "https://api.mapbox.com/geocoding/v5/mapbox.places/" + me.direccionIncidente + ".json?access_token=" + me.accessToken)
                        .then((response) => {
                            if (!response) {
                                console.error("Invalid response:");
                                console.error(response);
                                reject("Invalid response");
                            } else {

                                me.lat = parseFloat(response.data.features[0].center[0]);
                                me.lang = parseFloat(response.data.features[0].center[1]);
                                //Formato estilo GeoJson para guardar la info del incidente en una variable del componente y poder mostr el marcador en el mapa
                                let newData = {
                                    type: "Feature",
                                    geometry: {
                                        type: "Point",
                                        coordinates: response.data.features[0].center,
                                    },
                                    properties: {
                                        title: 'localizacion',
                                        description: 'INCIDENT: ' + response.data.features[0].place_name
                                    }
                                };

                                let lugar = me.incidentGeocoder;
                                lugar.push(newData);
                                resolve({ lat: me.lat, lang: me.lang });
                            }
                        })
                        .catch((error) => {
                            console.error(error);
                            reject(error);
                        });
                });

            }

            //Actualizar latitud y longitud en variable global del componente
            if(me.direccionIncidente !== ""){
            getMapData(me).then(({ lat, lang }) => {
                me.lat = lat
                me.lang = lang
            });
        }
        },

        cargarImagenesPersonalizadas(me, map) {
            //Imagen marker transito
            me.addImageMarker(map, 'transit', 'data:image/jpeg;base64,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')
            //Imagen marker mossos
            me.addImageMarker(map, 'mossos', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSFauoqR0cwPDZulkn-nGOaGbwjwDwmr4Z8Sg&usqp=CAU')
            //Imagen marker bomberos
            me.addImageMarker(map, 'bomber', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQMAAADCCAMAAAB6zFdcAAAAq1BMVEX////hChzgAAD8///gAAvrdXjui5HhAADhABfgABLfABTlR0332dXfAATshYrpa27yurfxpannUFjiLjjwmJ3pOkT89/j65uXvnqDiIyznWF73xMf30dHgAAjlQ0n32NTnXmXzyMrsk5f38fLzur3qb3Hre4DpW2ToY2z53uDwr7Lup6nvgojoaXLfEyH11dflJzftmZfmLT797O3iHSjqjIriUFTzyMbtjpTNpe4oAAAHWElEQVR4nO2aa3fiNhCG7ZHBNtQQLjEQFmpzS7inS7Lh//+yakaWMYnpyZ4DnPb0fT5ssT0aS69GM5JTxwEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADAvxqlru/yRn5vxrzdbtcXV3W5HGuf4z+v6vOmjEdhSE9Xdfknhdrnj6v6vCn1muuG19XgD3JdFxpAA2gADZz/rgYqnjWSpDF0CmVdKbXvV5Kk0tqfqv102estD9r+YfGcVFN+oNsunl4aD9PMxGqglv3K87bfK2wVlIqHx+Zzc5Lam6qnWcb6ybDxVInFZrlqNJ+TSv/Duc8mI9Ng7UbkeUSD4emRWnWIIs8bkd+Z2d5sIiLaOrMdRaG2H/ccZ8i/Q6JwXdRgP03IH4Ujoso0dxkfQ/K90PNpsDIe1U77i2bOvqMf1LQGal8nX/vm3uz6dxFBNEjeKXCFGrXy/raj7KbrBvQUm7vVyHWjzZpq5oG3i1u2bUCTggar3ShrTIND5nI5sLbauGnG19F3KE197TBwdTysKHQLr72HCKyBuyNXz6Mn742G2QQ9kgwyu09jlWsQdCI38ExXw7o28zwzNFrmGrhB4OoYEKm8jtk+L0fcRr9pxOZ+onINVqaJG6ue/NKhocOCf2zupYHuUL3br5hJqsmMqwpJf+f9/lbu00JZDXRn6XWRmAkLtQLbRScSLxuVa+DW/EWr+yj36S9514AbULvbb+xkfCurgTsI9JxrtadqwQ1Gq6kT7+tsFB0u9fzaGsgKUIcBX/gtznNLHkhNolj1BjJvy1wD118rnQo7IsIoYZs+2weDkwa116lOaapr5pVNJrJGOBsq1fY40pXVQI/1rbFaH2PFl57IrZyaTkantXljDUaVLFhl7uv8u+Llsa3XsUzOItfAb4h5KuMLJeVJ54MgzjWgnmk7F0d6gcUuR73Jm2rKq8fnvGg0oHdTKBQbZbVa9efzeXN9+4wgGpANuBcJPz0o6bC3dQr3g7fYapBpcxDJEmPSGNl1JBpksjpmgY/0sv4gXkR2RKxx+GQ18DInjpKQo+THncqiwBoEP+0LZ3Z7IxFBM2slOcvnmZWc6JoiEfts1DUmEvSnOKC8yI658rSVc2QNOulQSEWy0Gpgg8ZRiywTu0/99A6pQGANzPpj8qEPz/d6ex6tnzqZBoOiBn1j8kkD347K2XIMDRwnCaXaZZhiczAa6Md2yzR1TUkNuHzU1/G9NKCqvTpQlq9nfiEdaG18GxYXNeifa3DK5xuZcf2mU9238AskCc5PHZo+UZRtPnRZ2qU3Hj9zroEsXnqwi+KkAf2uBnmKkZXPeUAyT43O2Gf7g0axS/vGo35kFKtFdzh3yFqY27Wwt/OdmjpmreQyStVvaJDr95ytBVkT42mviGM0iM404CqdTsZkqk79PnUhCO17Wrb7ZlH0bZ/6kg94ar+twcq+4Y2DXe8JeXsQvH0ZUZkG/Ep1qJJ7FlA3w9TGLOAU53Beu3qnzNl8bDskV4/887sa5PM3lA2FPkn8EOt95jJ+0AzVZw0+kmbzJSurzjG6owa1R7NBkRLovfDFpFAc1UrCQAb7XQ30Bkiaxj9rWWiptzDbEvAsv3M6eP+iQRrxgTE7aEp37MWtNXCp3tP9apnNPWcBNfWlkrXM8Lh+1aa/pYHZEvbG7LP2yi7XMq0Jz+tBTiPE1ucaxDILfJpSJvpqnTvtEwOuQq/ky/bkl3lgjnI+dV4jsy5n+ZnpWxroc1XU2Zn0bpKrkkNQGLmPOxrlLc81UA0RZzeZzbquV8xJN9YgbJOcdSUPj7L1p45mOs39IJoUzs7f0OBt7OU+bembdrJTtTkivORn52JOlBOobKXkoDZ2bs/YC0NaT+yHCwryXauz8k9fRzz7IalKYei9WQ3CWnjaJ4b6CT+Qv7Hsem9Z65AaNpzjJPtIocMt2pi7r9yBwleC+Ik8+6HFo+TW42e29Xp9vFYfbdmzuO9x4ePf9NgxW5nOJL/dH+sGvzINxtLYPFjzRZ0fLB/1j7YTV3fk68b1j4LLHwn78ync7rMz1TP76BZ6pD5esp1U+JLe5c+WSuABp7PZPv78sKfvpgf11f7Lhcovcpfx8mM27J2PQsX74exjf5L6zGFGvNSvHS7jewgAAAAFZtUix0nXsCpPR4dNtcx+Uu5bdaufzI31vtw8bZRZd9dXGullNlRKUq7B/pOZb/4TlftWr6W+o4dy89YXQ/n39ofnhu+W4DXLrZdUZi2fzst4rJVaX9SgzDpsX2Wc/wQ0gAYMNIAGDDSABgw0gAYMNIAGDDSABgw0gAYMNLixBq9BqfX/SoMBNIAGzn9Gg43vlUAv5dZ/UJm1d+GbqrMrNb+sQal1/Srj/CdW80oJ8wt/9e81y6wr23JrZ1FufeF/NRtuS82PN/+urMq5ivkF4981v8YwAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD+TfwNxCSQTWqcZ9YAAAAASUVORK5CYII=')
            //Imagen marker policia local
            me.addImageMarker(map, 'pLocal', 'data:image/png;base64,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')
            //Imagen marker policia municipal
            me.addImageMarker(map, 'pMunicipal', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRpGMuq1MLSmXrRoI2ItobTgsQZo2lyNRO80g&usqp=CAU')
            //Imagen marker Guardia Urbana
            me.addImageMarker(map, 'gUrbana', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAZIAAAB9CAMAAAC/ORUrAAAAwFBMVEX///8AJIQAAHsAHIEAEH4AIIM+WqDK1eeOncQAIoMAAHoABH0AFYEAGYEADH8AH4J6hLLU3Orr8PaEl8C8wddUa6cYN44tUJstQJD1+fy9y+CZp8qCirbj5e4KMYvf5/NNYKCyv9lVcatCUZZ7j7uOlLs3TJWss8/W2OWlsc9YZ6MAKolsf7Oip8fw9PmVnMEsRpR3jLtje7Fzfa+4u9NMXJ0XO48lS5htg7ZLZqUUQ5Vgd6+2xN01SZRLZaWrutarIa2IAAAVLElEQVR4nO2dCVfiMNeAadpS0iapLYIWBorK6LAMAzo6Kir//1+9zXLTRUoF/b6X99h75syxS7b7NMnNdmk0vlJa4/BL46ulsYh/9U6dwcF6XTL2OPiy3AxOlURfFuX/mIS/R10LcZldr5xDYggYxoTMg0NzMGmOhDTl5V9X5Aa5h34iPRXf6H6cuz++F3evdhQyvpIhr77uC9tbwjlh1MbYSAT7lnd2AJSxxQMT1jkQShtRIUhennqGyI13KJILFR9lndz9Sybuur3yoIErQ7q/Dkz78zLxiKABgk33dt/2Iup6vghLUeegWta2ZOKevPw8Ek+VhhaQUHEX7UKCZEj0X0OyQjkgQtj1vqoI2ktEbKEDNDoASgHJ3++MZO69J2IYZLl/v+r8oJIKRfu3XnUt0dJCW4BwJqNDYuvNZx61zev9Q9ZIQBxNBJOkQ0SWDddoclCE0el65m72D1dEgkwh6PshWfoq56Y1DsLwV2tG1A27e+iQINwcELKIZHnF5Wz57ZBMIONmN5Z3oqnSjeEdVk0OlAKST8v/KpLo3FStlp0Oi87UPfPqgAgXYRgudr2RvBDlLxfi+sNIZBKV9fAQJCpveSRRIcf75oW/Uho+fBe+Bz1JtkbEyijGCDC12kLGLxBsLG+0cpHFk/nVkHqIDK9vJ9mB70K93k/ysBoi9AgPwv79jCG65GP+ApJAhRnnkghPV6MHmyFmLKet3VbdB5HEMp3xhg+YDYR+N/JI4vErRWw4nbzTauS0psuZhTz6MFr1so+z2tlMuxayup33vetg83jfTdRlza7X/VRdd1T1G2b25Q4tcJoxi4v7Q133XXHNcKah742oR0wbY5zERhh9SjUWy9e9f43ozLMxmav7E0PMGWCfoHajVRiXyDBWdkIlns8QoT7m4lPGXndZER9E4sh00G0jfGA2ZgUkfWL5vEQUdQvV6s+Dx6hvG0lebEpQt51CUdqZRY3BK6LJKzz8c/4Lcp5slFGXBUO5aKbsK6udfX3DsO1TwtwTdaMrX7N+AhImQQ61vhZviOZnAChqaVXKkY951rjlAQHJytXWnYF+tEgeyXsjuKWGojoJE43K24yPIpFFIXeNJs9AHslggnSKPupnYukZzM/mxbDZUOvclXe60YBS/YLJMuPnaF1Ql0Fd2XZA2gbKDbcHrje8nj72T+OPIhkMiVEUjIAzIGnKJlEh6WcnDTBZ2hVI1lsmGayrUiZ7IjlxhCJzSNiNnU0SvehI+igPhIuPQF2AZHFNM8/Noe5jF0v2riw4qaiJTJi6NHMli3qFKfoqJNES0jaJpT9lDKAVEr85FuAkkgHVH6DNywdXZUPFFXw+dlLNtUK8XPX+BJIfb+Y7JHiYNDqmCdrzZ5CXF8gLppaln5uveSTnLZZTuqer2T/4gH3C0pqPLpJHP9SjKtuqCklbpW2is5+/T2xLZZI+5ZDYZ0OcIpnrbCHjmVn6qylB4rjyDRvNTn7/XEKNse2yarIfElP1nzkkSSdg0beOCZ80U9P80N5ji1z++fOGTNBpL4sEz5Li+pTCU95wK/UhKPnz+PePIUQvKLypr43efQpJqCaS6bWsum0LTLYcEkM1AwJJCMWm54GACkxKGq6mLJltXIjLHnybpeOL/ZAYSnF5JKoSguWBkeTfV1GrljmE4TZ5zCLhHT87X98uoa6AOoCo/xDkosMksbueVaUhq08h2cgi2A/QWCr9YS/IIZEZsdA6jcLwuzLQI1Sa7bUkVNXC6+WzYFjZTvcTSCQXy80h0YYIWIOTbPG01mL1Pp3mkCTvCwunD02A6mx6qmgE2sG1ava50T1U71rKOHJu8nL6MSR3LkLMIqmJ9VuV03OKSGz0r93n2mhCNm5kkKCApFBLXpIkPEasZ0hioOLUefo8Et9ttvpBBglmSmmhqpPmm7jy3CQvSXl1h6v0A+0/IAFmT1BWmeaYq4sRD+zZxMJVRU9aAMgMU5/a2GVZQU8fQ9IInZfJ71VTm23qE4dvOkXim/CVq68+Ndpe/RySYl+yCHqb3+OO7s0H9lcjMbuQf0BCRxBIN5ui5Yri3sXNz9unqPDYv46ySDAN81kBky1R1+b3yZseVcGA3UvugKYACYygi4WpHpfkpQwJNsF2h1KbutSXMD6Vl1VLWAv8xUgyhYHMpe35H6kYbG2fMQAkrzkk0I7xbQkyzYvtWYXZeJYgmRUars8jieLAOe1D41hEYumm7eVdqVf5oeKO9ZIwSWIy/mokLJ1S0uMS3U9t8t9YKoukvDetB9Vf52sJgX0YgbL3MwMbEK6uyS2oK0HSBVWp9uBzSILJ7XUXk6SdhVFKAQn2YTDVuFGaEBZOLu2dtSS8WDUfZtRDDHYLfBUSe5ha0+lQEe7AF5RmN5Hen7fzmcmSblRZSQUkmmisuqI8kvhmftY1SNKvaHUlSM7A4rrLq+UAJGHLQHzOJzcqLSDJDH9+AoC/JUi21ZLNGfIsM59EORJtZX8ISfatINO2K+2rO+nEU7BGyOLlzRqTBSRQ72L6DknUH7pySjCrrk1qfUGL3mY2F3htDyR9yvSgHfswOiogIXoKuDFWzRQCo64aibPUY7LMmLoUiR5h08uPILEy3/97JNDaQ1sUXqZzVNg2S2oJIAneIdnMPD1oxybMRfD0VK9l2DMZVxsPheyNZA3Tc5h6rnmmursikswYYlWF5F3DNWGwtGMy13seGRV9yUtJLVlvR5JtVMprier6ghkMNZJRFuqOVAdQisQs9CWPejaTr2Wk6tpk2tv8jpJQdfsfRrKGuTJCby/CUosrbaX0VM6Ha8nGhVka1JwkuQ2rune9FJSaskKgljgFJJmOu6rhGsAkic+6LScqM4JLkehdWsTqbAZpHeTphfpZbpNmyPZDAs02tsaLrD6LSFL96/qZ9qC7keicWiP59cDorRSJ3ufhP+fuj6T64CPUSDJz4e+RvORssKZqiGhX6Xg/JD09En0MczdEeteqGcMkm2vIQDkSK4ek66skIA+nkEahlqRIwOJKzeLxzvWSsQJG1ur1QRWSgZ47tLK3IzUhBSumu5CkdjEYweKjAg2m7fbZXkjuYTAPZc8h0SZWbg1rRLcjIVB81eyoPDnvhn16QqUUCeSCgoob8/xQsVBLHtS3w2AarXJCJdSbanOLQbHsBTBEtBOJ7vta2aEiTMelI7/u1nFJCZKBaoPS2qtHPRzJAJBgL+3eJu86RkByq67VVKZCMnk3A1gyVMwgCVW2/XMYDlxBH7cNCcyA+TC/rbvBUiTRq94O1czcXhOlj6gaCYUV6fyEiooYe5D1CCbbP4QEmvk0521oIERDeQmzfZioCKJ0+2MRCXCF2WSFBMYYGsliCCuEpUgasGQF3Wqgx0vbkPxSKeolBz3/UooknVs2rDW0MItHFZFe/9+FRO9kG9DMtGOkSocZIIFv8mNI9By2zjkst0skAdhcSeGv+47Ta5+na5AaybmfTWQCVUshgeUE3Qjp5nAHkhNYPVN7VZ8KSPJ9yS/oEWHbhoOqxiWpyZUAGD6eOoHzdz6EnOlGZxcSg7XymfVuskggCNzYEwm9V4/hCwdzYpVOTpuWxzwrs6iskYDhjEmz/fjAMM5N4+rlYiqtmIme+N2BRBtE5D5O3mjqeYPtDRcAU0qK9SpxOZJ0IydfHPYYYZ4OZT7AF74TCZY9MKyv2XIJ6xr2uckGMYKud8+GCxqIi3T1Tll4z9kl+xSPmUXS0l2OSQjfBCMXWhQSXQKb9gdhr4MwjGx3INEb+AwTzWbJuNyHMNuQ6O7d8B6DRTD2bBv21pQj6eX2T+SmLtKeuRyJTXjjZE87FHQmV6TSFtFq9sJBf0gwDFP26t6TyFuDhXOLsJ9ruPjmki1MyGhtZpAEbu4pvRz5GSRgBPNEEELUIJdGbg1wK5KXVGFcveRSjU+3G8Er/VGQJAmSmAVNvwpJY1xyKMBA6SpqKRI8EzsKsKlnTqBqpajNJC/MTvK+lxGsa5VhCXXRs1f5nME4KH6/4cd6EIZZOhXRzGLDKJYtGSB5KRB7iCotrkY65JeFe4aJoO21ZJHOCXHcSTuujGbyo1Eu6cRFVmw3s65diiRpIs9yH6vNYIrjTStMZBm1X2Rm/eWHkDj53U8+ia/8fIBG1EFm9iUfPUXCyEiRDNJ9FsnjU2WxpjtUMkXH1kPYUM2KmkHZjqTRzBgSdhxau2pJUo40B8nX+QLNR2EGqyATkxWh2MzIbpIsQ8JXxsNuhgm19BuLbuYgoZ1UOWVJgD7eI8lPO/azXwox4sa9LFs6bk4+8ysE25H4VksR1RUj3lS/4XSZeAGbbJYo9pqRRCxDzwma8nHSAKNO8qn8E8+JaiBiV1wRN4+kMffkJiYTPQSNGMmX1DaOvxAGknCWavIVU7R0eLnEc2u2C0kjXHUR0b160hWibju3i9xRybpZJEna3ij5Y3CmPtZEK804fWHRURsvsc+MCd+LqPIupwQg6xqJZ8nH0INtZp6O9ynks7by+bqRkWDcpOJY83C9kVme3J3cZXZ/LFpLceh52eI6+nkiRZcubF15PPxw7qjAQiT2UF3dFQ8HOPMZQi5a9qPMS/LRL7jUSUSbKW+7EZmK4yv6hYo99Ive+H4oD2wj3Bz3Cq8HEE1G44Pk3p1U30QV666wnOjMeZwuuhbqWCh13EmuECWECfOP+Z1+k/F4Z2vxzoUKkT8OzjfWB0Gw6xxCFAfxjufRYHfw7YHCfQLxt0sPBlQFDAbRQUFLihUteIwHRJiN99v6WailllpqqaWWWmqppZZaaqmlllpqqaWWWmqppZZaaimXqN/aLWWnytMY2p+NIa6K4bQqBqcihvZBXnL/SxIqt05lwoZV616huzMCCy2r8tCriEH7OyqVflUM+c+iP07l5+8d3usOkHiVRNqqfq9cFt1tu2oy2znOK5GU7ZVSku7iLZNeRQw7twUJ6bPdMbA8kiuPaLEYIpX1eA/puYSw1+r3yuVbImmauacYzUviPUB4YQ5xwJjK90WCKSGUyq077tf5Fa2RSDkACe6uE7kdidO09u69YPtIjUTKAUjgxFjMDyJg68tMshqJlE8gkSegtB8Op3U7nV7q/XfC89Ki0Z8q/z7R6aozXedcry7+Pk6ntzewJzOPxGl3pp0xRB79TWLbJHdX0+lj9gBx/7YzvVydyt1iNRIeFitPmJuuJ37cxEIz2busXMbc8BERuVG5zx2wUspYR++16xv8FvFoOy0MIAmWniViO5f6X8wQQ2ZjJULozaa9V8Z4rMSzhfFcI+EXyqfTo+tjbFK+89h2hXr4KWO2cdWB55HL/Wbwf8RW21U7yOaGgpmYbc0ICqOQXLiJ8WCS5D9f2g9c2fbzRB3gR3K3aT95CxIVmL4vkmbM5Rc/TYqlu1l+ahaz+3mHYuXUTyDxk9cFklu+l99HiDs/pfLYAv+dEd+4XY2SDLB1HknA9+xb948dhpMUekrZuDujnidtCh6FcFnJrm/XM36Pb9//tkgMm4q96pS7m5CNzjPF8uS+w89eiBPu4ix+8mkzpLJpLp2w92Cqn63gh/vsGa8w/HSmUHuKhJ9uF05juFt/kyMUysbk/uXiledAbLN/IxiLA+4xf8gdGX1fJKIB4k2Iv5aN0GI4s9lI/Hnmq+PJyonuw5+NPDuFbd6NB8TA5r+GOPKKLdkmJYabcAyikQT8XI8888uNOn60RCjb50P7kJ8p4wij5sym0lUCPy/Di/qdkWB5ChbP9E8PRrH8i7sMFsdvBBIqHDoKx4XqzPI5InbS4YdJBcLKhSZ3A4KySHi9UedLuRd5fqhbKFsefuNZgNMmoXIby5GcfGckvid+IpA39ISm5z6cm/Z8yj0tpUhQ6gJDnasaBDH3be3wzsWcCuG+hnhPoJFwv8PK1y+nw10GCGVL3y3TDJLGwrloPa7P7W+OxLyPuIQTfvTNx9KofWkaHj8wZWeRKP8L4iS5lz1S17gRZ/dM8Xt/PAjXsUbCXUionPPz0NxAEMp2RZ2YUunvNBGnM2M8Ud69f2sk2iPUCw/LeAcbvfFTcITNlvwIuUaiPPoJTz55JCJZzDwQ9yaDZJkiEUMfL9qOZO5SG1NmnPN69q2RpC54eD8vmxquOevRCfN9iTrF3d9SSwTN3i8HJMwg4QdsyYkOiun2WsLPgWOr0wvrviRFwrtt7ge8J4YlF/BCiuRPmkvlbfDxdsUXS0X3gvIK0kjuiPZKK/qSf1uRxPxosPQ9s6qRQFjeIPFhA3doZUq3JtxqLSKJuU0gTaiQ8pUvaYSBI5Wbh9H6JM4g4Xmyh+IZ97uklZ1Hwscsyl9Y7q3viMS/igeDQexI12vc8Ro3kSQo8fUXkQgzF1sX/OdgiDKHuSsede7+mprEjTJIBvz7Fye3F5YitwUJr0ASiXCM+K2RCF8b/MS2GJpwy1R4E+LmcCx+jOUdEuGmwybT+dICL0PCi4S1DmLnPolHuMBJR+9zPjBZho0FZ0342GYLEpFzblqH4jeGvjeSjMifIxIOgGzzbYS9hy0NV6Nxy5lgk4rJKLk2POa3KPO582WrCYWRSBb89zd845X/TqUtZsy2IAmE3wzafJqJRGskYgRvEzUnK/yHJCpHt3wMIiaeckgaa/BrYuqZ9TH3WMGbHP/9THBs8BbLtw3bkhPHC651pJGIoeJccfZGPCSdf1MkZygVjzys1dh9sU7GiRYy242Nm4zreR+x4n+krow2r4hZSc++TNfqe/cI8VtdlUSP+3w4VxGeYP4+wo9qcQrz+QKB5J5HLKy7FUnCe2zeCHjIh2+KZJCTjOeHwaTd2oTccQMXXrjCC41g0mpPgpxOBptWu+9oN48QVCrntNVu9bQ/cPEs0n8tdHjxK5Qq5LdEctxSIzk6qZEcndRIjk5qJEcnNZKjk9BGu2VYFcPC3R2B+1wVQ68qhmokVTH8LyFpOBXSa1bIU1ARw+asIobbqhj6VTGMq2IYV0Rw9rWHTP5vZUGouUvIeVUMgbszApPtdGHK5bQiBlT608kgbW93DEU/oEctYeFnyYviVyOp6Cp2e5XlcurtjsGqRmLtjgHVSGokn5AaydFJjeTopEZydFIjOTqpkRyd1EiOTmokRyc1kqOTGsnRSY3k6KRGcnRSIzk6qZEcndRIjk5qJEcnNZKjkxrJ0UmN5OikRnJ0UiM5OqmRHJ3USI5OaiRHJzWSo5MaydFJjeTopEZydFIjOTqpkfz/yn8AoCc7qCjqgaoAAAAASUVORK5CYII=')
            //Imagen marker Atencion Ciutadana
            me.addImageMarker(map, 'atCiutadana', 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/25/Info_icon-72a7cf.svg/2048px-Info_icon-72a7cf.svg.png')
            //Imagen marker incidente
            me.addImageMarker(map, 'incidencia', 'https://static.vecteezy.com/system/resources/previews/009/663/747/original/warning-icon-transparent-free-png.png')
        },

        cargarMarcadores(me, map) {
            map.on('load', function () {
                //CARGAR IMAGENES PERSONALIZADAS PARA MARCADORES
                me.cargarImagenesPersonalizadas(me, map)

                //AÑADIR MARCADORES
                me.addMarker(me, map, 'transitPoint', 'transit', 'localizaciones1', 0.15)
                me.addMarker(me, map, 'mossosPoint', 'mossos', 'localizaciones2', 0.15)
                me.addMarker(me, map, 'bombersPoint', 'bomber', 'localizaciones3', 0.15)
                me.addMarker(me, map, 'pLocalPoint', 'pLocal', 'localizaciones4', 0.15)
                me.addMarker(me, map, 'pMunicipalPoint', 'pMunicipal', 'localizaciones5', 0.15)
                me.addMarker(me, map, 'gUrbanaPointer', 'gUrbana', 'localizaciones6', 0.15)
                me.addMarker(me, map, 'atCiutadanaPinter', 'atCiutadana', 'localizaciones7', 0.03)
                me.addMarker(me, map, 'incidenciaPoint', 'incidencia', 'incidentGeocoder', 0.03)

                // CREAR POPUPS
                const popup = new mapboxgl.Popup({
                    closeButton: true,
                    closeOnClick: true
                });

                me.setPopup(me, map, popup, 'gUrbana')
                me.setPopup(me, map, popup, 'incidencia')
                me.setPopup(me, map, popup, 'pMunicipal')
                me.setPopup(me, map, popup, 'pLocal')
                me.setPopup(me, map, popup, 'bomber')
                me.setPopup(me, map, popup, 'transit')
                me.setPopup(me, map, popup, 'mossos')
                me.setPopup(me, map, popup, 'atCiutadana')
            });
        },

        //Metodo para obtener GeoJsons de las localizaciones e imprimir los marcadores personalizados
        async marcasAgencias() {
            let me = this;
            this.coordIncidente()
            me.mapa.remove();
            const map = me.imprimirMapa()
            me.mapa = map


            //CREAR DISTINTOS GEOJSONS PARA CADA TIPO DE AGENCIA Y IMPRIMIR MARCADORES PERSONALIZADOS
            await Promise.all(this.localizaciones.map(async localizacion => {
                //si el codigo postal tiene solo 4 digitos, se le añade un 0 delante (está mal en la base de datos)
                let codiPostal = localizacion.codi_postal
                if (codiPostal.toString().length == 4) {
                    codiPostal = '0' + codiPostal.toString()
                } else if (codiPostal.toString().length < 4) {
                    alert('DANGER!! el codigo postal tiene menos de 4 digitos')
                }

                //guardar todas las localizaciones con sus coordenadas creadas con API forwardGeocode en cada JSON segun el tipo de agencia
                let direccion = codiPostal + ", " + localizacion.carrer
                axios
                    .get("https://api.mapbox.com/geocoding/v5/mapbox.places/" + direccion + ".json?access_token=" + me.accessToken)
                    .then((response) => {
                        if (!response) {

                            console.error('Invalid response:');
                            console.error(response);
                            return;
                        }
                        else {

                            //guardar la info de cada agencia con formato GeoJson incluyendo el boton para seleccionar esa agencia en concreto
                            let newData = {
                                type: 'Feature',
                                geometry: {
                                    type: 'Point',
                                    coordinates: response.data.features[0].center
                                },
                                properties: {
                                    title: localizacion,
                                    description: localizacion.nom + '<div class="d-grid gap-2 col-6 mx-auto"><button type="button" class="btn btn-primary btn-sm" id="botonPop" tipo-agencia="' + localizacion.agencies_primaries_id + '" tipo-loc="localizaciones' + localizacion.agencies_primaries_id + '" agencia-id="' + localizacion.id + '"> Seleccionar  </button></div>',
                                }
                            };

                            //si se recibe más de una seleccion, se guardan en jsons distintos para poder asignarles luego un marcador personalizado correspondiente
                            let id = localizacion.agencies_primaries_id;
                            this.seleccion.forEach(element => {
                                let seleccion = [];
                                switch (id) {
                                    case 1:
                                        seleccion = this.localizaciones1
                                        break;
                                    case 2:
                                        seleccion = this.localizaciones2
                                        break;
                                    case 3:
                                        seleccion = this.localizaciones3
                                        break;
                                    case 4:
                                        seleccion = this.localizaciones4
                                        break;
                                    case 5:
                                        seleccion = this.localizaciones5
                                        break;
                                    case 6:
                                        seleccion = this.localizaciones6
                                        break;
                                    case 7:
                                        seleccion = this.localizaciones7
                                        break;
                                }
                                seleccion.push(newData);
                            })
                        }
                    });
            }));

            //Se cargan los marcadores en el mapa
            me.cargarMarcadores(me, map)

            //reseteo variable confirmar en componente padre (AgenciasPrimarias) para que vuelva a hacer la impresión de los marcadores al hacer nueva selección en checkbox.
            me.$emit('restarSend')
        },

        //Creación y impresión del mapa standard (en primer lugar se centra en Barcelona, luego en la incidencia una vez se tiene su dirección)
        imprimirMapa() {
            mapboxgl.accessToken = this.accessToken;

            const map = new mapboxgl.Map({
                container: "mapContainer",
                style: "mapbox://styles/mapbox/streets-v11",
                center: [this.lat, this.lang],
                zoom: 12,
            });
            return map
        }
    },

    //MOUNTED COMPONENT
    mounted() {
        this.seleccionAgencias = this.agenciasFinales
        this.mapa = this.imprimirMapa()
    },
};

</script>

<style scoped></style>