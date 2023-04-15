<template lang="">
    <div id="container">
        <svg viewBox="-1 -1 256 256">
            <g>
                <path class="st0 outline1" d="M253.7,0.3L126.7,0C56.6,0-0.3,56.6-0.3,126.5S56.6,253,126.7,253s127-56.6,127-126.5L253.7,0.3z"/>
                <path class="st0 outline2" d="M234,20.1c-1.9-0.1-3.2-0.2-4.5-0.2c-34.6,0-69.2,0-103.8,0c-3.5-0.1-7.1,0.1-10.6,0.5
                    C53,28.1,11.9,80.9,20.2,142.4c6.4,47.8,48.8,87.2,97.5,90.5c64.1,4.3,116.1-43.6,116.4-107.3c0.1-19.2,0-38.3,0-57.5L234,20.1z"/>
                <path class="st1 phone" d="M121,215.2c-14.8,0-25-4.1-33.4-12.2c-13-12.6-25.9-25.2-38.4-38.2c-11.7-12.2-14.9-26.8-10-43
                    c1.9-6.2,4.2-12.3,6.6-18.3c3.5-9.4,14-14.2,23.4-10.8c2.2,0.8,4.3,2,6,3.6c4.5,4,8.7,8.3,12.8,12.7c5,5.3,6,11.7,3.9,18.6
                    c-0.4,1.6-1,3.2-1.8,4.7c-2.3,4.2-1.3,7.1,2.3,10.3c6.5,5.8,12.6,12,18.5,18.4c2.9,3.1,5.4,3.6,9.2,2c3.2-1.5,6.6-2.5,10.1-3
                    c12.3-1.1,31.8,17.8,31.5,30c-0.2,6.3-2.6,12-8.2,14.6C142.3,209.8,130.8,214.7,121,215.2z M139.8,189.6l0.4-1.7
                    c-1.8-1.5-3.8-2.8-5.2-4.6c-2.5-3.1-5-3.7-8.6-1.9c-3.1,1.6-6.6,2.5-9.9,3.7c-3.3-0.1-6.4-1.4-9.3-4.6
                    C95.5,169,83.6,158.3,72,146.7c-3.4-3.4-5.1-7-4-11.8c1.5-3.9,2.6-6.4,3.8-10.4c0.3-1.6-0.1-3.3-1-4.7c-1.8-2.6-4-4.8-7-8.1
                    c-2.4,7.2-4.9,12.8-6.2,18.8c-2.1,10.8-3.6,16,3.4,23c12.5,12.4,26.5,25.4,39,37.8c5,5,12.7,5,19.7,3.7
                    C126.6,193.9,133.1,191.5,139.8,189.6z"/>
                <g>
                    <path class="st2" d="M167.8,78.6c0-10.5,0-21,0-31.6c-0.1-1.9,0-3.7,0.3-5.6c1-4.2,3.3-7.2,8.3-7c4.5,0.1,7.7,3.1,7.9,8.1
                        c0.3,6.5,0.1,13.1,0.1,19.6c0,1.8-0.2,3.1,2.6,2c20.4-8,34.9-0.4,39.1,20.5c2.2,10.7,0.4,20.9-6.6,29.8c-7.3,9.2-22,13.5-32.1,5.5
                        c-1.5-1.2-2.7-2.7-4.5-0.1c-2.4,3.6-6,4.4-9.9,2.9c-4.4-1.7-5.3-5.6-5.4-9.8C167.8,101.5,167.8,90,167.8,78.6z M210.2,92.3
                        c0-9.8-5.4-17-12.9-17s-13.1,7.4-12.9,17.1c0.2,10,5.7,16.8,13.4,16.5C205.3,108.6,210.2,102.1,210.2,92.3z"/>
                    <path class="st3" d="M139.6,92.4c0,7,0.1,14,0,21c-0.2,6.7-3,9.9-8.4,9.7c-5-0.2-7.7-3.2-7.8-9.6c-0.1-14.2-0.1-28.5,0-42.8
                        c0.1-6.6,3.2-10,8.4-9.8c5,0.2,7.6,3.3,7.8,9.7C139.8,77.9,139.6,85.2,139.6,92.4z"/>
                    <path class="st4" d="M154.7,83c5-0.1,9.6,4.3,9.6,9.2c-0.1,5.1-4.2,9.2-9.3,9.4c-5.1-0.1-9.3-4.1-9.6-9.2
                        C145.1,87.7,149.7,83.1,154.7,83z"/>
                    <path class="st3" d="M140.7,45.5c-0.1,5-4.8,9.5-9.5,9.3c-4.8-0.3-9.2-5.2-8.9-10c0.2-4.5,4.8-8.9,9.3-8.8
                        C136.8,36.1,140.8,40.4,140.7,45.5z"/>
                </g>
            </g>
        </svg>
        <div id="loading-text">
            <h2>Carregant<span class="dot dot-1">.</span><span class="dot dot-2">.</span><span class="dot dot-3">.</span></h2>
        </div>
  
    </div>
</template>
<script>
export default {

}
</script>
<style scoped>
#container {
    position: absolute;
    height: 100%;
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-color: white;
    z-index: 100;
}

svg {
    width: auto;
    height: 100px;
    float: left;
}
.phone {
    fill: rgba(0, 0, 0, 1);
    stroke: black;
    transform-origin: center;
    animation: ringing 2s ease-in-out infinite;
}
.outline1, .outline2 {
    fill: none;
    stroke: black;
    stroke-width: 4px;
}
.outline1 {
    stroke-dasharray: 850.57;
    stroke-dashoffset: 0;
    animation: reveal1 3s infinite;
}
.outline2 {
    stroke-dasharray: 718.75;
    stroke-dashoffset: 0;
    animation: reveal2 3s infinite;
}
.st2{fill:#18AEC5;stroke:#000000;stroke-miterlimit:10;}
.st3{fill:#FCC519;stroke:#000000;stroke-miterlimit:10;}
.st4{fill:#E01C7E;stroke:#000000;stroke-miterlimit:10;}

.dot {
    opacity: 0;
    animation: appear 2s ease-in-out infinite;
}  .dot-2 {
    animation-delay: 0s;
}
  .dot-2 {
    animation-delay: 0.5s;
}
  .dot-3 {
    animation-delay: 1s;
}
  @keyframes appear {
    40% {
        opacity: 1;
    }
    80%, 100% {
        opacity: 0;
    }

}

@keyframes reveal1 {
    0%, 100% {
      stroke-dashoffset: 850.57;
    }
    50% {
      stroke-dashoffset: 0;
    }
    50.1% {
        stroke-dashoffset: 1701.14;
    }
  }
  @keyframes reveal2 {
    0%, 100% {
      stroke-dashoffset: 718.75;
    }
    50% {
      stroke-dashoffset: 0;
    }
    50.1% {
        stroke-dashoffset: 1437.5;
    }
  }
@keyframes ringing {
    0% {
        transform: rotate(0);
    }
    10% {
        transform: rotate(4deg);
    }
    20% {
        transform: rotate(-4deg);
    }
    30% {
        transform: rotate(4deg);
    }
    40% {
        transform: rotate(-4deg);
    }
    50% {
        transform: rotate(0);
    }
    100% {
        transform: rotate(0);
    }
}
</style>