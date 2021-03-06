/**
 * datatransformer_echart
 *
 * visuals extension of datatransformer that uses the 
 * graphics library echart.
 *
 * echart web: 		https://ecomfe.github.io/echarts/index-en.html
 * echart github: 	https://github.com/ecomfe/echarts
 * 
 *
 * @author	vigor situ lou
 * @version 0.1.0
 * @date 	27/abr/2016
 *
 */

'use strict';

(function ($, datatransformer, echarts) {
    // Contants
    var FIXED_NUMBER = 2;

    // Themes
    var _themes = { blue: { color: ["#1790cf", "#1bb2d8", "#99d2dd", "#88b0bb", "#1c7099", "#038cc4", "#75abd0", "#afd6dd"], title: { textStyle: { fontWeight: "normal", color: "#1790cf" } }, dataRange: { color: ["#1178ad", "#72bbd0"] }, toolbox: { color: ["#1790cf", "#1790cf", "#1790cf", "#1790cf"] }, tooltip: { backgroundColor: "rgba(0,0,0,0.5)", axisPointer: { type: "line", lineStyle: { color: "#1790cf", type: "dashed" }, crossStyle: { color: "#1790cf" }, shadowStyle: { color: "rgba(200,200,200,0.3)" } } }, dataZoom: { dataBackgroundColor: "#eee", fillerColor: "rgba(144,197,237,0.2)", handleColor: "#1790cf" }, grid: { borderWidth: 0 }, categoryAxis: { axisLine: { lineStyle: { color: "#1790cf" } }, splitLine: { lineStyle: { color: ["#eee"] } } }, valueAxis: { axisLine: { lineStyle: { color: "#1790cf" } }, splitArea: { show: !0, areaStyle: { color: ["rgba(250,250,250,0.1)", "rgba(200,200,200,0.1)"] } }, splitLine: { lineStyle: { color: ["#eee"] } } }, timeline: { lineStyle: { color: "#1790cf" }, controlStyle: { normal: { color: "#1790cf" }, emphasis: { color: "#1790cf" } } }, k: { itemStyle: { normal: { color: "#1bb2d8", color0: "#99d2dd", lineStyle: { width: 1, color: "#1c7099", color0: "#88b0bb" } } } }, map: { itemStyle: { normal: { areaStyle: { color: "#ddd" }, label: { textStyle: { color: "#c12e34" } } }, emphasis: { areaStyle: { color: "#99d2dd" }, label: { textStyle: { color: "#c12e34" } } } } }, force: { itemStyle: { normal: { linkStyle: { color: "#1790cf" } } } }, chord: { padding: 4, itemStyle: { normal: { borderWidth: 1, borderColor: "rgba(128, 128, 128, 0.5)", chordStyle: { lineStyle: { color: "rgba(128, 128, 128, 0.5)" } } }, emphasis: { borderWidth: 1, borderColor: "rgba(128, 128, 128, 0.5)", chordStyle: { lineStyle: { color: "rgba(128, 128, 128, 0.5)" } } } } }, gauge: { axisLine: { show: !0, lineStyle: { color: [[.2, "#1bb2d8"], [.8, "#1790cf"], [1, "#1c7099"]], width: 8 } }, axisTick: { splitNumber: 10, length: 12, lineStyle: { color: "auto" } }, axisLabel: { textStyle: { color: "auto" } }, splitLine: { length: 18, lineStyle: { color: "auto" } }, pointer: { length: "90%", color: "auto" }, title: { textStyle: { color: "#333" } }, detail: { textStyle: { color: "auto" } } }, textStyle: { fontFamily: "Arial, Verdana, sans-serif" } }, dark: { backgroundColor: "#1b1b1b", color: ["#FE8463", "#9BCA63", "#FAD860", "#60C0DD", "#0084C6", "#D7504B", "#C6E579", "#26C0C0", "#F0805A", "#F4E001", "#B5C334"], title: { textStyle: { fontWeight: "normal", color: "#fff" } }, legend: { textStyle: { color: "#ccc" } }, dataRange: { itemWidth: 15, color: ["#FFF808", "#21BCF9"], textStyle: { color: "#ccc" } }, toolbox: { color: ["#fff", "#fff", "#fff", "#fff"], effectiveColor: "#FE8463", disableColor: "#666" }, tooltip: { backgroundColor: "rgba(250,250,250,0.8)", axisPointer: { type: "line", lineStyle: { color: "#aaa" }, crossStyle: { color: "#aaa" }, shadowStyle: { color: "rgba(200,200,200,0.2)" } }, textStyle: { color: "#333" } }, dataZoom: { dataBackgroundColor: "#555", fillerColor: "rgba(200,200,200,0.2)", handleColor: "#eee" }, grid: { borderWidth: 0 }, categoryAxis: { axisLine: { show: !1 }, axisTick: { show: !1 }, axisLabel: { textStyle: { color: "#ccc" } }, splitLine: { show: !1 } }, valueAxis: { axisLine: { show: !1 }, axisTick: { show: !1 }, axisLabel: { textStyle: { color: "#ccc" } }, splitLine: { lineStyle: { color: ["#aaa"], type: "dashed" } }, splitArea: { show: !1 } }, polar: { name: { textStyle: { color: "#ccc" } }, axisLine: { lineStyle: { color: "#ddd" } }, splitArea: { show: !0, areaStyle: { color: ["rgba(250,250,250,0.2)", "rgba(200,200,200,0.2)"] } }, splitLine: { lineStyle: { color: "#ddd" } } }, timeline: { label: { textStyle: { color: "#ccc" } }, lineStyle: { color: "#aaa" }, controlStyle: { normal: { color: "#fff" }, emphasis: { color: "#FE8463" } }, symbolSize: 3 }, line: { smooth: !0 }, k: { itemStyle: { normal: { color: "#FE8463", color0: "#9BCA63", lineStyle: { width: 1, color: "#FE8463", color0: "#9BCA63" } } } }, radar: { symbol: "emptyCircle", symbolSize: 3 }, pie: { itemStyle: { normal: { borderWidth: 1, borderColor: "rgba(255, 255, 255, 0.5)" }, emphasis: { borderWidth: 1, borderColor: "rgba(255, 255, 255, 1)" } } }, map: { itemStyle: { normal: { borderColor: "rgba(255, 255, 255, 0.5)", areaStyle: { color: "#ddd" }, label: { textStyle: {} } }, emphasis: { areaStyle: { color: "#FE8463" }, label: { textStyle: {} } } } }, force: { itemStyle: { normal: { linkStyle: { color: "#fff" } } } }, chord: { itemStyle: { normal: { borderWidth: 1, borderColor: "rgba(228, 228, 228, 0.2)", chordStyle: { lineStyle: { color: "rgba(228, 228, 228, 0.2)" } } }, emphasis: { borderWidth: 1, borderColor: "rgba(228, 228, 228, 0.9)", chordStyle: { lineStyle: { color: "rgba(228, 228, 228, 0.9)" } } } } }, gauge: { axisLine: { show: !0, lineStyle: { color: [[.2, "#9BCA63"], [.8, "#60C0DD"], [1, "#D7504B"]], width: 3, shadowColor: "#fff", shadowBlur: 10 } }, axisTick: { length: 15, lineStyle: { color: "auto", shadowColor: "#fff", shadowBlur: 10 } }, axisLabel: { textStyle: { fontWeight: "bolder", color: "#fff", shadowColor: "#fff", shadowBlur: 10 } }, splitLine: { length: 25, lineStyle: { width: 3, color: "#fff", shadowColor: "#fff", shadowBlur: 10 } }, pointer: { shadowColor: "#fff", shadowBlur: 5 }, title: { textStyle: { fontWeight: "bolder", fontSize: 20, fontStyle: "italic", color: "#fff", shadowColor: "#fff", shadowBlur: 10 } }, detail: { shadowColor: "#fff", shadowBlur: 5, offsetCenter: [0, "50%"], textStyle: { fontWeight: "bolder", color: "#fff" } } }, funnel: { itemStyle: { normal: { borderColor: "rgba(255, 255, 255, 0.5)", borderWidth: 1 }, emphasis: { borderColor: "rgba(255, 255, 255, 1)", borderWidth: 1 } } }, textStyle: { fontFamily: "Arial, Verdana, sans-serif" } }, gray: { color: ["#757575", "#c7c7c7", "#dadada", "#8b8b8b", "#b5b5b5", "#e9e9e9"], title: { textStyle: { fontWeight: "normal", color: "#757575" } }, dataRange: { color: ["#636363", "#dcdcdc"] }, toolbox: { color: ["#757575", "#757575", "#757575", "#757575"] }, tooltip: { backgroundColor: "rgba(0,0,0,0.5)", axisPointer: { type: "line", lineStyle: { color: "#757575", type: "dashed" }, crossStyle: { color: "#757575" }, shadowStyle: { color: "rgba(200,200,200,0.3)" } } }, dataZoom: { dataBackgroundColor: "#eee", fillerColor: "rgba(117,117,117,0.2)", handleColor: "#757575" }, grid: { borderWidth: 0 }, categoryAxis: { axisLine: { lineStyle: { color: "#757575" } }, splitLine: { lineStyle: { color: ["#eee"] } } }, valueAxis: { axisLine: { lineStyle: { color: "#757575" } }, splitArea: { show: !0, areaStyle: { color: ["rgba(250,250,250,0.1)", "rgba(200,200,200,0.1)"] } }, splitLine: { lineStyle: { color: ["#eee"] } } }, timeline: { lineStyle: { color: "#757575" }, controlStyle: { normal: { color: "#757575" }, emphasis: { color: "#757575" } } }, k: { itemStyle: { normal: { color: "#8b8b8b", color0: "#dadada", lineStyle: { width: 1, color: "#757575", color0: "#c7c7c7" } } } }, map: { itemStyle: { normal: { areaStyle: { color: "#ddd" }, label: { textStyle: { color: "#c12e34" } } }, emphasis: { areaStyle: { color: "#99d2dd" }, label: { textStyle: { color: "#c12e34" } } } } }, force: { itemStyle: { normal: { linkStyle: { color: "#757575" } } } }, chord: { padding: 4, itemStyle: { normal: { borderWidth: 1, borderColor: "rgba(128, 128, 128, 0.5)", chordStyle: { lineStyle: { color: "rgba(128, 128, 128, 0.5)" } } }, emphasis: { borderWidth: 1, borderColor: "rgba(128, 128, 128, 0.5)", chordStyle: { lineStyle: { color: "rgba(128, 128, 128, 0.5)" } } } } }, gauge: { axisLine: { show: !0, lineStyle: { color: [[.2, "#b5b5b5"], [.8, "#757575"], [1, "#5c5c5c"]], width: 8 } }, axisTick: { splitNumber: 10, length: 12, lineStyle: { color: "auto" } }, axisLabel: { textStyle: { color: "auto" } }, splitLine: { length: 18, lineStyle: { color: "auto" } }, pointer: { length: "90%", color: "auto" }, title: { textStyle: { color: "#333" } }, detail: { textStyle: { color: "auto" } } }, textStyle: { fontFamily: "Arial, Verdana, sans-serif" } }, green: { color: ["#408829", "#68a54a", "#a9cba2", "#86b379", "#397b29", "#8abb6f", "#759c6a", "#bfd3b7"], title: { textStyle: { fontWeight: "normal", color: "#408829" } }, dataRange: { color: ["#1f610a", "#97b58d"] }, toolbox: { color: ["#408829", "#408829", "#408829", "#408829"] }, tooltip: { backgroundColor: "rgba(0,0,0,0.5)", axisPointer: { type: "line", lineStyle: { color: "#408829", type: "dashed" }, crossStyle: { color: "#408829" }, shadowStyle: { color: "rgba(200,200,200,0.3)" } } }, dataZoom: { dataBackgroundColor: "#eee", fillerColor: "rgba(64,136,41,0.2)", handleColor: "#408829" }, grid: { borderWidth: 0 }, categoryAxis: { axisLine: { lineStyle: { color: "#408829" } }, splitLine: { lineStyle: { color: ["#eee"] } } }, valueAxis: { axisLine: { lineStyle: { color: "#408829" } }, splitArea: { show: !0, areaStyle: { color: ["rgba(250,250,250,0.1)", "rgba(200,200,200,0.1)"] } }, splitLine: { lineStyle: { color: ["#eee"] } } }, timeline: { lineStyle: { color: "#408829" }, controlStyle: { normal: { color: "#408829" }, emphasis: { color: "#408829" } } }, k: { itemStyle: { normal: { color: "#68a54a", color0: "#a9cba2", lineStyle: { width: 1, color: "#408829", color0: "#86b379" } } } }, map: { itemStyle: { normal: { areaStyle: { color: "#ddd" }, label: { textStyle: { color: "#c12e34" } } }, emphasis: { areaStyle: { color: "#99d2dd" }, label: { textStyle: { color: "#c12e34" } } } } }, force: { itemStyle: { normal: { linkStyle: { color: "#408829" } } } }, chord: { padding: 4, itemStyle: { normal: { borderWidth: 1, borderColor: "rgba(128, 128, 128, 0.5)", chordStyle: { lineStyle: { color: "rgba(128, 128, 128, 0.5)" } } }, emphasis: { borderWidth: 1, borderColor: "rgba(128, 128, 128, 0.5)", chordStyle: { lineStyle: { color: "rgba(128, 128, 128, 0.5)" } } } } }, gauge: { axisLine: { show: !0, lineStyle: { color: [[.2, "#86b379"], [.8, "#68a54a"], [1, "#408829"]], width: 8 } }, axisTick: { splitNumber: 10, length: 12, lineStyle: { color: "auto" } }, axisLabel: { textStyle: { color: "auto" } }, splitLine: { length: 18, lineStyle: { color: "auto" } }, pointer: { length: "90%", color: "auto" }, title: { textStyle: { color: "#333" } }, detail: { textStyle: { color: "auto" } } }, textStyle: { fontFamily: "Arial, Verdana, sans-serif" } }, helianthus: { backgroundColor: "#F2F2E6", color: ["#44B7D3", "#E42B6D", "#F4E24E", "#FE9616", "#8AED35", "#ff69b4", "#ba55d3", "#cd5c5c", "#ffa500", "#40e0d0", "#E95569", "#ff6347", "#7b68ee", "#00fa9a", "#ffd700", "#6699FF", "#ff6666", "#3cb371", "#b8860b", "#30e0e0"], title: { backgroundColor: "#F2F2E6", itemGap: 10, textStyle: { color: "#8A826D" }, subtextStyle: { color: "#E877A3" } }, dataRange: { x: "right", y: "center", itemWidth: 5, itemHeight: 25, color: ["#E42B6D", "#F9AD96"], text: ["高", "低"], textStyle: { color: "#8A826D" } }, toolbox: { color: ["#E95569", "#E95569", "#E95569", "#E95569"], effectiveColor: "#ff4500", itemGap: 8 }, tooltip: { backgroundColor: "rgba(138,130,109,0.7)", axisPointer: { type: "line", lineStyle: { color: "#6B6455", type: "dashed" }, crossStyle: { color: "#A6A299" }, shadowStyle: { color: "rgba(200,200,200,0.3)" } } }, dataZoom: { dataBackgroundColor: "rgba(130,197,209,0.6)", fillerColor: "rgba(233,84,105,0.1)", handleColor: "rgba(107,99,84,0.8)" }, grid: { borderWidth: 0 }, categoryAxis: { axisLine: { lineStyle: { color: "#6B6455" } }, splitLine: { show: !1 } }, valueAxis: { axisLine: { show: !1 }, splitArea: { show: !1 }, splitLine: { lineStyle: { color: ["#FFF"], type: "dashed" } } }, polar: { axisLine: { lineStyle: { color: "#ddd" } }, splitArea: { show: !0, areaStyle: { color: ["rgba(250,250,250,0.2)", "rgba(200,200,200,0.2)"] } }, splitLine: { lineStyle: { color: "#ddd" } } }, timeline: { lineStyle: { color: "#6B6455" }, controlStyle: { normal: { color: "#6B6455" }, emphasis: { color: "#6B6455" } }, symbol: "emptyCircle", symbolSize: 3 }, bar: { itemStyle: { normal: { barBorderRadius: 0 }, emphasis: { barBorderRadius: 0 } } }, line: { smooth: !0, symbol: "emptyCircle", symbolSize: 3 }, k: { itemStyle: { normal: { color: "#E42B6D", color0: "#44B7D3", lineStyle: { width: 1, color: "#E42B6D", color0: "#44B7D3" } } } }, scatter: { itemStyle: { normal: { borderWidth: 1, borderColor: "rgba(200,200,200,0.5)" }, emphasis: { borderWidth: 0 } }, symbol: "circle", symbolSize: 4 }, radar: { symbol: "emptyCircle", symbolSize: 3 }, map: { itemStyle: { normal: { areaStyle: { color: "#ddd" }, label: { textStyle: { color: "#E42B6D" } } }, emphasis: { areaStyle: { color: "#fe994e" }, label: { textStyle: { color: "rgb(100,0,0)" } } } } }, force: { itemStyle: { normal: { nodeStyle: { borderColor: "rgba(0,0,0,0)" }, linkStyle: { color: "#6B6455" } } } }, chord: { itemStyle: { normal: { chordStyle: { lineStyle: { width: 0, color: "rgba(128, 128, 128, 0.5)" } } }, emphasis: { chordStyle: { lineStyle: { width: 1, color: "rgba(128, 128, 128, 0.5)" } } } } }, gauge: { center: ["50%", "80%"], radius: "100%", startAngle: 180, endAngle: 0, axisLine: { show: !0, lineStyle: { color: [[.2, "#44B7D3"], [.8, "#6B6455"], [1, "#E42B6D"]], width: "40%" } }, axisTick: { splitNumber: 2, length: 5, lineStyle: { color: "#fff" } }, axisLabel: { textStyle: { color: "#fff", fontWeight: "bolder" } }, splitLine: { length: "5%", lineStyle: { color: "#fff" } }, pointer: { width: "40%", length: "80%", color: "#fff" }, title: { offsetCenter: [0, -20], textStyle: { color: "auto", fontSize: 20 } }, detail: { offsetCenter: [0, 0], textStyle: { color: "auto", fontSize: 40 } } }, textStyle: { fontFamily: "Arial, Verdana, sans-serif" } }, infographic: { color: ["#C1232B", "#B5C334", "#FCCE10", "#E87C25", "#27727B", "#FE8463", "#9BCA63", "#FAD860", "#F3A43B", "#60C0DD", "#D7504B", "#C6E579", "#F4E001", "#F0805A", "#26C0C0"], title: { textStyle: { fontWeight: "normal", color: "#27727B" } }, dataRange: { x: "right", y: "center", itemWidth: 5, itemHeight: 25, color: ["#C1232B", "#FCCE10"] }, toolbox: { color: ["#C1232B", "#B5C334", "#FCCE10", "#E87C25", "#27727B", "#FE8463", "#9BCA63", "#FAD860", "#F3A43B", "#60C0DD"], effectiveColor: "#ff4500" }, tooltip: { backgroundColor: "rgba(50,50,50,0.5)", axisPointer: { type: "line", lineStyle: { color: "#27727B", type: "dashed" }, crossStyle: { color: "#27727B" }, shadowStyle: { color: "rgba(200,200,200,0.3)" } } }, dataZoom: { dataBackgroundColor: "rgba(181,195,52,0.3)", fillerColor: "rgba(181,195,52,0.2)", handleColor: "#27727B" }, grid: { borderWidth: 0 }, categoryAxis: { axisLine: { lineStyle: { color: "#27727B" } }, splitLine: { show: !1 } }, valueAxis: { axisLine: { show: !1 }, splitArea: { show: !1 }, splitLine: { lineStyle: { color: ["#ccc"], type: "dashed" } } }, polar: { axisLine: { lineStyle: { color: "#ddd" } }, splitArea: { show: !0, areaStyle: { color: ["rgba(250,250,250,0.2)", "rgba(200,200,200,0.2)"] } }, splitLine: { lineStyle: { color: "#ddd" } } }, timeline: { lineStyle: { color: "#27727B" }, controlStyle: { normal: { color: "#27727B" }, emphasis: { color: "#27727B" } }, symbol: "emptyCircle", symbolSize: 3 }, line: { itemStyle: { normal: { borderWidth: 2, borderColor: "#fff", lineStyle: { width: 3 } }, emphasis: { borderWidth: 0 } }, symbol: "circle", symbolSize: 3.5 }, k: { itemStyle: { normal: { color: "#C1232B", color0: "#B5C334", lineStyle: { width: 1, color: "#C1232B", color0: "#B5C334" } } } }, scatter: { itemStyle: { normal: { borderWidth: 1, borderColor: "rgba(200,200,200,0.5)" }, emphasis: { borderWidth: 0 } }, symbol: "star4", symbolSize: 4 }, radar: { symbol: "emptyCircle", symbolSize: 3 }, map: { itemStyle: { normal: { areaStyle: { color: "#ddd" }, label: { textStyle: { color: "#C1232B" } } }, emphasis: { areaStyle: { color: "#fe994e" }, label: { textStyle: { color: "rgb(100,0,0)" } } } } }, force: { itemStyle: { normal: { linkStyle: { color: "#27727B" } } } }, chord: { itemStyle: { normal: { borderWidth: 1, borderColor: "rgba(128, 128, 128, 0.5)", chordStyle: { lineStyle: { color: "rgba(128, 128, 128, 0.5)" } } }, emphasis: { borderWidth: 1, borderColor: "rgba(128, 128, 128, 0.5)", chordStyle: { lineStyle: { color: "rgba(128, 128, 128, 0.5)" } } } } }, gauge: { center: ["50%", "80%"], radius: "100%", startAngle: 180, endAngle: 0, axisLine: { show: !0, lineStyle: { color: [[.2, "#B5C334"], [.8, "#27727B"], [1, "#C1232B"]], width: "40%" } }, axisTick: { splitNumber: 2, length: 5, lineStyle: { color: "#fff" } }, axisLabel: { textStyle: { color: "#fff", fontWeight: "bolder" } }, splitLine: { length: "5%", lineStyle: { color: "#fff" } }, pointer: { width: "40%", length: "80%", color: "#fff" }, title: { offsetCenter: [0, -20], textStyle: { color: "auto", fontSize: 20 } }, detail: { offsetCenter: [0, 0], textStyle: { color: "auto", fontSize: 40 } } }, textStyle: { fontFamily: "Arial, Verdana, sans-serif" } }, macarons: { color: ["#2ec7c9", "#b6a2de", "#5ab1ef", "#ffb980", "#d87a80", "#8d98b3", "#e5cf0d", "#97b552", "#95706d", "#dc69aa", "#07a2a4", "#9a7fd1", "#588dd5", "#f5994e", "#c05050", "#59678c", "#c9ab00", "#7eb00a", "#6f5553", "#c14089"], title: { textStyle: { fontWeight: "normal", color: "#008acd" } }, dataRange: { itemWidth: 15, color: ["#5ab1ef", "#e0ffff"] }, toolbox: { color: ["#1e90ff", "#1e90ff", "#1e90ff", "#1e90ff"], effectiveColor: "#ff4500" }, tooltip: { backgroundColor: "rgba(50,50,50,0.5)", axisPointer: { type: "line", lineStyle: { color: "#008acd" }, crossStyle: { color: "#008acd" }, shadowStyle: { color: "rgba(200,200,200,0.2)" } } }, dataZoom: { dataBackgroundColor: "#efefff", fillerColor: "rgba(182,162,222,0.2)", handleColor: "#008acd" }, grid: { borderColor: "#eee" }, categoryAxis: { axisLine: { lineStyle: { color: "#008acd" } }, splitLine: { lineStyle: { color: ["#eee"] } } }, valueAxis: { axisLine: { lineStyle: { color: "#008acd" } }, splitArea: { show: !0, areaStyle: { color: ["rgba(250,250,250,0.1)", "rgba(200,200,200,0.1)"] } }, splitLine: { lineStyle: { color: ["#eee"] } } }, polar: { axisLine: { lineStyle: { color: "#ddd" } }, splitArea: { show: !0, areaStyle: { color: ["rgba(250,250,250,0.2)", "rgba(200,200,200,0.2)"] } }, splitLine: { lineStyle: { color: "#ddd" } } }, timeline: { lineStyle: { color: "#008acd" }, controlStyle: { normal: { color: "#008acd" }, emphasis: { color: "#008acd" } }, symbol: "emptyCircle", symbolSize: 3 }, bar: { itemStyle: { normal: { barBorderRadius: 5 }, emphasis: { barBorderRadius: 5 } } }, line: { smooth: !0, symbol: "emptyCircle", symbolSize: 3 }, k: { itemStyle: { normal: { color: "#d87a80", color0: "#2ec7c9", lineStyle: { color: "#d87a80", color0: "#2ec7c9" } } } }, scatter: { symbol: "circle", symbolSize: 4 }, radar: { symbol: "emptyCircle", symbolSize: 3 }, map: { itemStyle: { normal: { areaStyle: { color: "#ddd" }, label: { textStyle: { color: "#d87a80" } } }, emphasis: { areaStyle: { color: "#fe994e" } } } }, force: { itemStyle: { normal: { linkStyle: { color: "#1e90ff" } } } }, chord: { itemStyle: { normal: { borderWidth: 1, borderColor: "rgba(128, 128, 128, 0.5)", chordStyle: { lineStyle: { color: "rgba(128, 128, 128, 0.5)" } } }, emphasis: { borderWidth: 1, borderColor: "rgba(128, 128, 128, 0.5)", chordStyle: { lineStyle: { color: "rgba(128, 128, 128, 0.5)" } } } } }, gauge: { axisLine: { lineStyle: { color: [[.2, "#2ec7c9"], [.8, "#5ab1ef"], [1, "#d87a80"]], width: 10 } }, axisTick: { splitNumber: 10, length: 15, lineStyle: { color: "auto" } }, splitLine: { length: 22, lineStyle: { color: "auto" } }, pointer: { width: 5 } }, textStyle: { fontFamily: "Arial, Verdana, sans-serif" } }, macarons2: { color: ["#ed9678", "#e7dac9", "#cb8e85", "#f3f39d", "#c8e49c", "#f16d7a", "#f3d999", "#d3758f", "#dcc392", "#2e4783", "#82b6e9", "#ff6347", "#a092f1", "#0a915d", "#eaf889", "#6699FF", "#ff6666", "#3cb371", "#d5b158", "#38b6b6"], dataRange: { color: ["#cb8e85", "#e7dac9"], textStyle: { color: "#333" } }, bar: { barMinHeight: 0, barGap: "30%", barCategoryGap: "20%", itemStyle: { normal: { barBorderColor: "#fff", barBorderRadius: 0, barBorderWidth: 1, label: { show: !1 } }, emphasis: { barBorderColor: "rgba(0,0,0,0)", barBorderRadius: 0, barBorderWidth: 1, label: { show: !1 } } } }, line: { itemStyle: { normal: { label: { show: !1 }, lineStyle: { width: 2, type: "solid", shadowColor: "rgba(0,0,0,0)", shadowBlur: 5, shadowOffsetX: 3, shadowOffsetY: 3 } }, emphasis: { label: { show: !1 } } }, symbolSize: 2, showAllSymbol: !1 }, k: { itemStyle: { normal: { color: "#fe9778", color0: "#e7dac9", lineStyle: { width: 1, color: "#f78766", color0: "#f1ccb8" } }, emphasis: {} } }, pie: { center: ["50%", "50%"], radius: [0, "75%"], clockWise: !1, startAngle: 90, minAngle: 0, selectedOffset: 10, itemStyle: { normal: { borderColor: "#fff", borderWidth: 1, label: { show: !0, position: "outer", textStyle: { color: "#1b1b1b" }, lineStyle: { color: "#1b1b1b" } }, labelLine: { show: !0, length: 20, lineStyle: { width: 1, type: "solid" } } } } }, map: { mapType: "china", mapLocation: { x: "center", y: "center" }, showLegendSymbol: !0, itemStyle: { normal: { borderColor: "#fff", borderWidth: 1, areaStyle: { color: "#ccc" }, label: { show: !1, textStyle: { color: "rgba(139,69,19,1)" } } }, emphasis: { borderColor: "rgba(0,0,0,0)", borderWidth: 1, areaStyle: { color: "#f3f39d" }, label: { show: !1, textStyle: { color: "rgba(139,69,19,1)" } } } } }, force: { itemStyle: { normal: { label: { show: !1 }, nodeStyle: { brushType: "both", strokeColor: "#a17e6e" }, linkStyle: { strokeColor: "#a17e6e" } }, emphasis: { label: { show: !1 }, nodeStyle: {}, linkStyle: {} } } }, gauge: { axisLine: { show: !0, lineStyle: { color: [[.2, "#ed9678"], [.8, "#e7dac9"], [1, "#cb8e85"]], width: 8 } }, axisTick: { splitNumber: 10, length: 12, lineStyle: { color: "auto" } }, axisLabel: { textStyle: { color: "auto" } }, splitLine: { length: 18, lineStyle: { color: "auto" } }, pointer: { length: "90%", color: "auto" }, title: { textStyle: { color: "#333" } }, detail: { textStyle: { color: "auto" } } } }, mint: { color: ["#8aedd5", "#93bc9e", "#cef1db", "#7fe579", "#a6d7c2", "#bef0bb", "#99e2vb", "#94f8a8", "#7de5b8", "#4dfb70"], dataRange: { color: ["#93bc92", "#bef0bb"] }, k: { itemStyle: { normal: { color: "#8aedd5", color0: "#7fe579", lineStyle: { width: 1, color: "#8aedd5", color0: "#7fe579" } }, emphasis: {} } }, pie: { itemStyle: { normal: { borderColor: "#fff", borderWidth: 1, label: { show: !0, position: "outer", textStyle: { color: "#1b1b1b" }, lineStyle: { color: "#1b1b1b" } }, labelLine: { show: !0, length: 20, lineStyle: { width: 1, type: "solid" } } } } }, map: { mapType: "china", mapLocation: { x: "center", y: "center" }, showLegendSymbol: !0, itemStyle: { normal: { borderColor: "#fff", borderWidth: 1, areaStyle: { color: "#ccc" }, label: { show: !1, textStyle: { color: "rgba(139,69,19,1)" } } }, emphasis: { borderColor: "rgba(0,0,0,0)", borderWidth: 1, areaStyle: { color: "#f3f39d" }, label: { show: !1, textStyle: { color: "rgba(139,69,19,1)" } } } } }, force: { itemStyle: { normal: { label: { show: !1 }, nodeStyle: { brushType: "both", strokeColor: "#49b485" }, linkStyle: { strokeColor: "#49b485" } }, emphasis: { label: { show: !1 }, nodeStyle: {}, linkStyle: {} } } }, gauge: { axisLine: { show: !0, lineStyle: { color: [[.2, "#93bc9e"], [.8, "#8aedd5"], [1, "#a6d7c2"]], width: 8 } }, axisTick: { splitNumber: 10, length: 12, lineStyle: { color: "auto" } }, axisLabel: { textStyle: { color: "auto" } }, splitLine: { length: 18, lineStyle: { color: "auto" } }, pointer: { length: "90%", color: "auto" }, title: { textStyle: { color: "#333" } }, detail: { textStyle: { color: "auto" } } } }, red: { color: ["#d8361b", "#f16b4c", "#f7b4a9", "#d26666", "#99311c", "#c42703", "#d07e75"], title: { textStyle: { fontWeight: "normal", color: "#d8361b" } }, dataRange: { color: ["#bd0707", "#ffd2d2"] }, toolbox: { color: ["#d8361b", "#d8361b", "#d8361b", "#d8361b"] }, tooltip: { backgroundColor: "rgba(0,0,0,0.5)", axisPointer: { type: "line", lineStyle: { color: "#d8361b", type: "dashed" }, crossStyle: { color: "#d8361b" }, shadowStyle: { color: "rgba(200,200,200,0.3)" } } }, dataZoom: { dataBackgroundColor: "#eee", fillerColor: "rgba(216,54,27,0.2)", handleColor: "#d8361b" }, grid: { borderWidth: 0 }, categoryAxis: { axisLine: { lineStyle: { color: "#d8361b" } }, splitLine: { lineStyle: { color: ["#eee"] } } }, valueAxis: { axisLine: { lineStyle: { color: "#d8361b" } }, splitArea: { show: !0, areaStyle: { color: ["rgba(250,250,250,0.1)", "rgba(200,200,200,0.1)"] } }, splitLine: { lineStyle: { color: ["#eee"] } } }, timeline: { lineStyle: { color: "#d8361b" }, controlStyle: { normal: { color: "#d8361b" }, emphasis: { color: "#d8361b" } } }, k: { itemStyle: { normal: { color: "#f16b4c", color0: "#f7b4a9", lineStyle: { width: 1, color: "#d8361b", color0: "#d26666" } } } }, map: { itemStyle: { normal: { areaStyle: { color: "#ddd" }, label: { textStyle: { color: "#c12e34" } } }, emphasis: { areaStyle: { color: "#99d2dd" }, label: { textStyle: { color: "#c12e34" } } } } }, force: { itemStyle: { normal: { linkStyle: { color: "#d8361b" } } } }, chord: { padding: 4, itemStyle: { normal: { borderWidth: 1, borderColor: "rgba(128, 128, 128, 0.5)", chordStyle: { lineStyle: { color: "rgba(128, 128, 128, 0.5)" } } }, emphasis: { borderWidth: 1, borderColor: "rgba(128, 128, 128, 0.5)", chordStyle: { lineStyle: { color: "rgba(128, 128, 128, 0.5)" } } } } }, gauge: { axisLine: { show: !0, lineStyle: { color: [[.2, "#f16b4c"], [.8, "#d8361b"], [1, "#99311c"]], width: 8 } }, axisTick: { splitNumber: 10, length: 12, lineStyle: { color: "auto" } }, axisLabel: { textStyle: { color: "auto" } }, splitLine: { length: 18, lineStyle: { color: "auto" } }, pointer: { length: "90%", color: "auto" }, title: { textStyle: { color: "#333" } }, detail: { textStyle: { color: "auto" } } }, textStyle: { fontFamily: "Arial, Verdana, sans-serif" } }, roma: { color: ["#E01F54", "#b8d2c7", "#f5e8c8", "#001852", "#c6b38e", "#a4d8c2", "#f3d999", "#d3758f", "#dcc392", "#2e4783", "#82b6e9", "#ff6347", "#a092f1", "#0a915d", "#eaf889", "#6699FF", "#ff6666", "#3cb371", "#d5b158", "#38b6b6"], dataRange: { color: ["#e01f54", "#e7dbc3"], textStyle: { color: "#333" } }, k: { itemStyle: { normal: { color: "#e01f54", color0: "#001852", lineStyle: { width: 1, color: "#f5e8c8", color0: "#b8d2c7" } } } }, pie: { itemStyle: { normal: { borderColor: "#fff", borderWidth: 1, label: { show: !0, position: "outer", textStyle: { color: "#1b1b1b" }, lineStyle: { color: "#1b1b1b" } }, labelLine: { show: !0, length: 20, lineStyle: { width: 1, type: "solid" } } }, emphasis: { borderColor: "rgba(0,0,0,0)", borderWidth: 1, label: { show: !1 }, labelLine: { show: !1, length: 20, lineStyle: { width: 1, type: "solid" } } } } }, map: { itemStyle: { normal: { borderColor: "#fff", borderWidth: 1, areaStyle: { color: "#ccc" }, label: { show: !1, textStyle: { color: "rgba(139,69,19,1)" } } }, emphasis: { borderColor: "rgba(0,0,0,0)", borderWidth: 1, areaStyle: { color: "#f3d999" }, label: { show: !1, textStyle: { color: "rgba(139,69,19,1)" } } } } }, force: { itemStyle: { normal: { label: { show: !1 }, nodeStyle: { brushType: "both", strokeColor: "#5182ab" }, linkStyle: { strokeColor: "#5182ab" } }, emphasis: { label: { show: !1 }, nodeStyle: {}, linkStyle: {} } } }, gauge: { axisLine: { show: !0, lineStyle: { color: [[.2, "#E01F54"], [.8, "#b8d2c7"], [1, "#001852"]], width: 8 } }, axisTick: { splitNumber: 10, length: 12, lineStyle: { color: "auto" } }, axisLabel: { textStyle: { color: "auto" } }, splitLine: { length: 18, lineStyle: { color: "auto" } }, pointer: { length: "90%", color: "auto" }, title: { textStyle: { color: "#333" } }, detail: { textStyle: { color: "auto" } } } }, sakura: { color: ["#e52c3c", "#f7b1ab", "#fa506c", "#f59288", "#f8c4d8", "#e54f5c", "#f06d5c", "#e54f80", "#f29c9f", "#eeb5b7"], dataRange: { color: ["#e52c3c", "#f7b1ab"] }, k: { itemStyle: { normal: { color: "#e52c3c", color0: "#f59288", lineStyle: { width: 1, color: "#e52c3c", color0: "#f59288" } }, emphasis: {} } }, pie: { itemStyle: { normal: { borderColor: "#fff", borderWidth: 1, label: { show: !0, position: "outer", textStyle: { color: "black" } }, labelLine: { show: !0, length: 20, lineStyle: { width: 1, type: "solid" } } } } }, map: { mapType: "china", mapLocation: { x: "center", y: "center" }, showLegendSymbol: !0, itemStyle: { normal: { borderColor: "#fff", borderWidth: 1, areaStyle: { color: "#ccc" }, label: { show: !1, textStyle: { color: "rgba(139,69,19,1)" } } }, emphasis: { borderColor: "rgba(0,0,0,0)", borderWidth: 1, areaStyle: { color: "#f3f39d" }, label: { show: !1, textStyle: { color: "rgba(139,69,19,1)" } } } } }, force: { itemStyle: { normal: { label: { show: !1 }, nodeStyle: { brushType: "both", strokeColor: "#e54f5c" }, linkStyle: { strokeColor: "#e54f5c" } }, emphasis: { label: { show: !1 }, nodeStyle: {}, linkStyle: {} } } }, gauge: { axisLine: { show: !0, lineStyle: { color: [[.2, "#e52c3c"], [.8, "#f7b1ab"], [1, "#fa506c"]], width: 8 } }, axisTick: { splitNumber: 10, length: 12, lineStyle: { color: "auto" } }, axisLabel: { textStyle: { color: "auto" } }, splitLine: { length: 18, lineStyle: { color: "auto" } }, pointer: { length: "90%", color: "auto" }, title: { textStyle: { color: "#333" } }, detail: { textStyle: { color: "auto" } } } }, shine: { color: ["#c12e34", "#e6b600", "#0098d9", "#2b821d", "#005eaa", "#339ca8", "#cda819", "#32a487"], title: { textStyle: { fontWeight: "normal" } }, dataRange: { itemWidth: 15, color: ["#1790cf", "#a2d4e6"] }, toolbox: { color: ["#06467c", "#00613c", "#872d2f", "#c47630"] }, tooltip: { backgroundColor: "rgba(0,0,0,0.6)" }, dataZoom: { dataBackgroundColor: "#dedede", fillerColor: "rgba(154,217,247,0.2)", handleColor: "#005eaa" }, grid: { borderWidth: 0 }, categoryAxis: { axisLine: { show: !1 }, axisTick: { show: !1 } }, valueAxis: { axisLine: { show: !1 }, axisTick: { show: !1 }, splitArea: { show: !0, areaStyle: { color: ["rgba(250,250,250,0.2)", "rgba(200,200,200,0.2)"] } } }, timeline: { lineStyle: { color: "#005eaa" }, controlStyle: { normal: { color: "#005eaa" }, emphasis: { color: "#005eaa" } } }, k: { itemStyle: { normal: { color: "#c12e34", color0: "#2b821d", lineStyle: { width: 1, color: "#c12e34", color0: "#2b821d" } } } }, map: { itemStyle: { normal: { areaStyle: { color: "#ddd" }, label: { textStyle: { color: "#c12e34" } } }, emphasis: { areaStyle: { color: "#e6b600" }, label: { textStyle: { color: "#c12e34" } } } } }, force: { itemStyle: { normal: { linkStyle: { color: "#005eaa" } } } }, chord: { itemStyle: { normal: { borderWidth: 1, borderColor: "rgba(128, 128, 128, 0.5)", chordStyle: { lineStyle: { color: "rgba(128, 128, 128, 0.5)" } } }, emphasis: { borderWidth: 1, borderColor: "rgba(128, 128, 128, 0.5)", chordStyle: { lineStyle: { color: "rgba(128, 128, 128, 0.5)" } } } } }, gauge: { axisLine: { show: !0, lineStyle: { color: [[.2, "#2b821d"], [.8, "#005eaa"], [1, "#c12e34"]], width: 5 } }, axisTick: { splitNumber: 10, length: 8, lineStyle: { color: "auto" } }, axisLabel: { textStyle: { color: "auto" } }, splitLine: { length: 12, lineStyle: { color: "auto" } }, pointer: { length: "90%", width: 3, color: "auto" }, title: { textStyle: { color: "#333" } }, detail: { textStyle: { color: "auto" } } }, textStyle: { fontFamily: "Arial, Verdana, sans-serif" } } },
        _themeList = [
                   "blue", "dark", "gray", "green", "helianthus",
                   "infographic", "macarons", "macarons2", "mint", "red",
                   "roma", "sakura", "shine"
        ],
        _formulas = ['sum', 'max', 'min'],
        _featureLabels = {
            restoreTitle: "restore",
            saveAsImageTitle: "save",
            markLabel: 'mark',
            markUndoLabel: 'mark undo',
            markClearLabel: 'mark clean',
            dataZoomLabel: 'zoom',
            dataZoomResetLabel: 'zoom reset',
            magicTypePie: "pie",
            magicTypeFunnel: "funnel",
            magicTypeBar: "bar",
            magicTypeLine: "line",
            magicTypeStack: "stack",
            magicTypeTiled: "tiled",
        },
        _message = {
            loading: "loading..."
        };

    // Private functions
    // Function that fixed a number
    function _toFixed(number) {
        if (isNaN(number))
            return number;

        return number.toFixed(FIXED_NUMBER);
    }

    // Function that give the closest number of a array of number
    function _getClosestNumber(number, arrayOfNumbers) {
        return arrayOfNumbers.reduce(function (prev, curr) {
            return (Math.abs(curr - number) < Math.abs(prev - number) ? curr : prev);
        });
    }

    /**
     *  _round
     *
     *	Function that round a number.
     * 
     *	@param 		Number 	a number
     *	@param		Number 	the number of digits to appear after the decimal point.
     *  @since 		0.1.0
     */
    function _round(number, digits) {
        if (typeof digits === 'undefined' || +digits === 0)
            return Math.round(number);

        number = +number;
        digits = +digits;

        if (isNaN(number) || !(typeof digits === 'number' && digits % 1 === 0))
            return NaN;

        // Shift
        number = number.toString().split('e');
        number = Math.round(+(number[0] + 'e' + (number[1] ? (+number[1] + digits) : digits)));

        // Shift back
        number = number.toString().split('e');
        return +(number[0] + 'e' + (number[1] ? (+number[1] - digits) : -digits));
    }

    /**
     *  echart-pie
     *
     *	Visual that handle a graphic pie with echart
     * 
     *  @since 		0.1.0
     */
    datatransformer.addVisual("echart-pie",
    {

        'subtitle': { label: "subtitle", type: String, required: false, order: 2 },
        'title': { label: "title", type: String, required: true, order: 1 },
        'group': { label: "group", type: datatransformer.typeGroup, required: true, order: 3 },
        'measure': { label: "measure", type: datatransformer.typeMeasure, required: true, order: 4 },
        'theme': { label: "theme", type: datatransformer.typeEnum, values: _themeList, required: true, order: 5 }
    },
    function () {
        this.render = function () {
            var _echartObj = echarts.init(document.getElementById(this.config.elemId)),
				_data = this.data.data,
				_measureObj = {},
				_dataOptionsObj = {},
				_generateData = [],
				_dataForPie = [],
				_leyends = [],
				_measureValues = [],
				_measureMaxvalue = 0;

            _echartObj.showLoading({ text: _message.loading });

            _leyends = this.util.getDistinct(_data, this.config.group);

            _measureObj[this.config.measure] = this.data.options.measures[this.config.measure];
            // this.config.groupFormula+"( "+this.util.generateMeasureColumn(this.config.measure)+" )";

            _dataOptionsObj = {
                groups: [this.config.group],
                measures: _measureObj
            };

            _generateData = this.util.generateDataTransformed(_data, _dataOptionsObj);

            for (var d in _generateData) {
                var _value = _toFixed(_generateData[d][this.config.measure]);
                _dataForPie.push({ value: _value, name: _generateData[d][this.config.group] });
                _measureValues.push(_value);
            }

            _measureMaxvalue = Math.max.apply(null, _measureValues);

            var optionForPie = {
                title: {
                    text: this.config.title,
                    subtext: this.config.subtitle,
                    x: "left"/*,
                    itemGap: -15*/
                },
                tooltip: {
                    trigger: "item",
                    formatter: "{a} <br/>{b} : {c}"
                },
                legend: {
                    orient: "horizontal",
                    x: "center",
                    data: _leyends
                },
                calculable: true,
                toolbox: {
                    show: true,
                    orient: 'vertical',
                    x: 'right',
                    y: 'top',
                    color: ['#1e90ff', '#22bb22', '#4b0082', '#d2691e'],
                    backgroundColor: 'rgba(0,0,0,0)',
                    borderColor: '#ccc',
                    borderWidth: 0,
                    padding: 5,
                    showTitle: true,
                    feature: {
                        magicType: {
                            show: true,
                            title: {
                                pie: _featureLabels.magicTypePie,
                                funnel: _featureLabels.magicTypeFunnel,
                            },
                            type: ['pie', 'funnel'],
                            option: {
                                funnel: {
                                    x: '10%',
                                    y: 60,
                                    y2: 60,
                                    width: '55%',
                                    min: 0,
                                    max: _measureMaxvalue,
                                    minSize: '0%',
                                    maxSize: '100%',
                                    sort: 'descending', // 'ascending', 'descending'
                                    gap: 10,
                                }
                            }
                        },
                        restore: {
                            show: true,
                            title: _featureLabels.restoreTitle,
                            color: 'black'
                        },
                        saveAsImage: {
                            show: true,
                            title: _featureLabels.saveAsImageTitle,
                            type: 'jpeg'
                        }
                    }
                },
                series: [
	                {
	                    name: this.config.group,
	                    type: "pie",
	                    radius: "55%",
	                    center: ["50%", "60%"],
	                    data: _dataForPie
	                }
                ]
            };

            _echartObj.setTheme(_themes[this.config.theme]);
            _echartObj.setOption(optionForPie);
            _echartObj.hideLoading();
        }
    });

    /**
     *  echart-bar
     *
     *	Visual that handle a graphic bar with echart
     * 
     *  @since 		0.1.0
     */
    datatransformer.addVisual("echart-bar",
    {
        title: { label: "title", type: String, required: true, order: 1 },
        subtitle: { label: "subtitle", type: String, required: false, order: 2 },
        group: { label: "group", type: datatransformer.typeGroup, required: true, order: 3 },
        measures: { label: "measures", type: datatransformer.typeMultipleMeasures, required: true, order: 4 },
        theme: { label: "theme", type: datatransformer.typeEnum, values: _themeList, required: true, order: 5 },
        horizontal: { label: "horizontal", type: Boolean, order: 6 },
    },
    function () {
        this.render = function () {
            var _echartObj = echarts.init(document.getElementById(this.config.elemId)),
				_data = this.data.data,
				_group = this.config.group,
				_measuresObj = {},
				_dataOptionsObj = {},
				_generateData = [],
				_dataForBar = [],
				_categories = [],
				_leyends = [];

            _echartObj.showLoading({ text: _message.loading });

            _categories = this.util.getDistinct(_data, _group);

            for (var m in this.config.measures) {
                _measuresObj[this.config.measures[m]] = this.data.options.measures[this.config.measures[m]];// this.config.groupFormula+"( "+this.util.generateMeasureColumn(this.config.measures[m])+" )";	
                _leyends.push(this.config.measures[m]);
            }

            _dataOptionsObj = {
                groups: [_group],
                measures: _measuresObj
            };

            _generateData = this.util.generateDataTransformed(_data, _dataOptionsObj);

            for (var m in this.config.measures) {
                var _measure = this.config.measures[m];
                var _categoryValues = [];

                for (var c in _categories) {
                    var _category = _categories[c];

                    var _value = _toFixed(_generateData.filter(function (x) {
                        return x[_group] == _category
                    })[0][_measure] || 0);

                    _categoryValues.push(_value);
                }

                _dataForBar.push({
                    "name": _measure,
                    "type": "bar",
                    "data": _categoryValues,
                    itemStyle: {
                        normal: {
                            borderRadius: 5,
                            label: {
                                show: true,
                                textStyle: {
                                    fontSize: '10',
                                    fontWeight: 'bold'
                                }
                            }
                        }
                    },
                });
            }

            var xAxisOption = [], yAxisOption = [];

            if (this.config.horizontal) {
                xAxisOption = [{ type: "value" }];
                yAxisOption = [{ type: "category", data: _categories }];
            }
            else {
                yAxisOption = [{ type: "value" }];
                xAxisOption = [{ type: "category", data: _categories }];
            }

            var optionForBar = {
                title: {
                    text: this.config.title,
                    subtext: this.config.subtitle,
                    x: "left"/*,
                    itemGap: -15*/
                },
                tooltip: {
                    trigger: "item",
                    formatter: "{a} <br/>{b} : {c}"
                },
                legend: {
                    orient: "horizontal",
                    x: "center",
                    data: _leyends
                },
                yAxis: yAxisOption,
                xAxis: xAxisOption,
                calculable: true,
                toolbox: {
                    show: true,
                    orient: 'vertical',
                    x: 'right',
                    y: 'center',
                    color: ['#1e90ff', '#22bb22', '#4b0082', '#d2691e'],
                    backgroundColor: 'rgba(0,0,0,0)',
                    borderColor: '#ccc',
                    borderWidth: 0,
                    padding: 5,
                    showTitle: true,
                    feature: {
                        mark: {
                            show: true,
                            title: {
                                mark: _featureLabels.markLabel,
                                markUndo: _featureLabels.markUndoLabel,
                                markClear: _featureLabels.markClearLabel
                            },
                            lineStyle: {
                                width: 1,
                                color: '#1e90ff',
                                type: 'dashed'
                            }
                        },
                        dataZoom: {
                            show: true,
                            title: {
                                dataZoom: _featureLabels.dataZoomLabel,
                                dataZoomReset: _featureLabels.dataZoomResetLabel
                            }
                        },
                        magicType: {
                            show: true,
                            title: {
                                bar: _featureLabels.magicTypeBar,
                                line: _featureLabels.magicTypeLine,
                                stack: _featureLabels.magicTypeStack,
                                tiled: _featureLabels.magicTypeTiled
                            },
                            type: ['bar', 'line', 'stack', 'tiled']
                        },
                        restore: {
                            show: true,
                            title: _featureLabels.restoreTitle,
                            color: 'black'
                        },
                        saveAsImage: {
                            show: true,
                            title: _featureLabels.saveAsImageTitle,
                            type: 'jpeg'
                        }
                    }
                },
                series: _dataForBar
            };

            _echartObj.setTheme(_themes[this.config.theme]);
            _echartObj.setOption(optionForBar);
            _echartObj.hideLoading();
        }
    });


    /**
     *  echart-line
     *
     *	Visual that handle a graphic line with echart
     * 
     *  @since 		0.1.0
     */
    datatransformer.addVisual("echart-line",
    {
        title: { label: "title", type: String, required: true, order: 1 },
        subtitle: { label: "subtitle", type: String, required: false, order: 2 },
        group: { label: "group", type: datatransformer.typeGroup, required: true, order: 3 },
        measures: { label: "measures", type: datatransformer.typeMultipleMeasures, required: true, order: 4 },
        theme: { label: "theme", type: datatransformer.typeEnum, values: _themeList, required: true, order: 5 },
        shadow: { label: "shadow", type: Boolean, order: 6 }
    },
    function () {
        this.render = function () {
            var _echartObj = echarts.init(document.getElementById(this.config.elemId)),
				_data = this.data.data,
				_group = this.config.group,
				_measuresObj = {},
				_dataOptionsObj = {},
				_generateData = [],
				_dataForBar = [],
				_categories = [],
				_leyends = [];

            _echartObj.showLoading({ text: _message.loading });

            _categories = this.util.getDistinct(_data, _group);

            for (var m in this.config.measures) {
                _measuresObj[this.config.measures[m]] = this.data.options.measures[this.config.measures[m]];
                //this.config.groupFormula+"( "+this.util.generateMeasureColumn(this.config.measures[m])+" )";	
                _leyends.push(this.config.measures[m]);
            }

            _dataOptionsObj = {
                groups: [_group],
                measures: _measuresObj
            };

            _generateData = this.util.generateDataTransformed(_data, _dataOptionsObj);

            for (var m in this.config.measures) {
                var _measure = this.config.measures[m];
                var _categoryValues = [];

                for (var c in _categories) {
                    var _category = _categories[c];

                    var _value = _toFixed(_generateData.filter(function (x) {
                        return x[_group] == _category
                    })[0][_measure] || 0);

                    _categoryValues.push(_value);
                }

                if (this.config.shadow) {
                    _dataForBar.push({
                        "name": _measure,
                        "type": "line",
                        "data": _categoryValues,
                        smooth: true,
                        itemStyle: {
                            normal: {
                                areaStyle: { type: 'default' },
                                borderRadius: 5,
                                label: {
                                    show: true,
                                    textStyle: {
                                        fontSize: '10',
                                        fontWeight: 'bold'
                                    }
                                }
                            },
                        },
                    });

                }
                else {
                    _dataForBar.push({
                        "name": _measure,
                        "type": "line",
                        "data": _categoryValues,
                        itemStyle: {
                            normal: {
                                borderRadius: 5,
                                label: {
                                    show: true,
                                    textStyle: {
                                        fontSize: '10',
                                        fontWeight: 'bold'
                                    }
                                }
                            },
                        },
                    });
                }
            }

            var optionForBar = {
                title: {
                    text: this.config.title,
                    subtext: this.config.subtitle,
                    x: "left"/*,
                    itemGap: -15*/
                },
                tooltip: {
                    trigger: "item",
                    formatter: "{a} <br/>{b} : {c}"
                },
                legend: {
                    orient: "horizontal",
                    x: "center",
                    data: _leyends
                },
                yAxis: {
                    type: 'value',
                    axisLabel: {
                        formatter: '{value}'
                    }
                },
                xAxis: [{ type: "category", boundaryGap: false, data: _categories }],
                calculable: true,
                toolbox: {
                    show: true,
                    orient: 'vertical',
                    x: 'right',
                    y: 'center',
                    color: ['#1e90ff', '#22bb22', '#4b0082', '#d2691e'],
                    backgroundColor: 'rgba(0,0,0,0)',
                    borderColor: '#ccc',
                    borderWidth: 0,
                    padding: 5,
                    showTitle: true,
                    feature: {
                        mark: {
                            show: true,
                            title: {
                                mark: _featureLabels.markLabel,
                                markUndo: _featureLabels.markUndoLabel,
                                markClear: _featureLabels.markClearLabel
                            },
                            lineStyle: {
                                width: 1,
                                color: '#1e90ff',
                                type: 'dashed'
                            }
                        },
                        dataZoom: {
                            show: true,
                            title: {
                                dataZoom: _featureLabels.dataZoomLabel,
                                dataZoomReset: _featureLabels.dataZoomResetLabel
                            }
                        },
                        magicType: {
                            show: true,
                            title: {
                                line: _featureLabels.magicTypeLine,
                                bar: _featureLabels.magicTypeBar,
                                stack: _featureLabels.magicTypeStack,
                                tiled: _featureLabels.magicTypeTiled
                            },
                            type: ['line', 'bar', 'stack', 'tiled']
                        },
                        restore: {
                            show: true,
                            title: _featureLabels.restoreTitle,
                            color: 'black'
                        },
                        saveAsImage: {
                            show: true,
                            title: _featureLabels.saveAsImageTitle,
                            type: 'jpeg'
                        }
                    }
                },
                series: _dataForBar
            };

            _echartObj.setTheme(_themes[this.config.theme]);
            _echartObj.setOption(optionForBar);
            _echartObj.hideLoading();
        }
    });



    /**
     *  echart-gauge-basic
     *
     *	Visual that handle a graphic gauge with echart
     * 
     *  @since 		0.1.0
     */
    datatransformer.addVisual("echart-gauge-basic",
    {
        title: { label: "title", type: String, required: true, order: 1 },
        subtitle: { label: "subtitle", type: String, required: false, order: 2 },
        group: { label: "group", type: datatransformer.typeGroup, required: true, order: 3 },
        filter: { label: "filter group", type: String, required: true, order: 4 },
        measure: { label: "measure", type: datatransformer.typeMeasure, required: true, order: 5 },
        min: { label: "min", type: Number, order: 6 },
        max: { label: "max", type: Number, order: 7 },
        theme: { label: "theme", type: datatransformer.typeEnum, values: _themeList, required: true, order: 8 }
    },
    function () {
        this.render = function () {
            var _echartObj = echarts.init(document.getElementById(this.config.elemId)),
				_data = this.data.data,
				_group = this.config.group,
				_filter = this.config.filter,
				_measureObj = {},
				_filterObj = {},
				_dataOptionsObj = {},
				_generateData = [],
				_dataForGauge = [],
				_categories = [],
				_serieS = [],
				_serieDatas = {};

            _echartObj.showLoading({ text: _message.loading });

            _measureObj[this.config.measure] = this.data.options.measures[this.config.measure];
            // "max ( "+this.util.generateMeasureColumn(this.config.measure)+" )";

            _filterObj[_group] = _filter

            _dataOptionsObj = {
                groups: [_group],
                filters: _filterObj,
                measures: _measureObj
            };

            _generateData = this.util.generateDataTransformed(_data, _dataOptionsObj);

            _dataForGauge.push(
			{
			    value: _toFixed(_generateData[0][this.config.measure] || 0),
			    name: _filter
			});

            _serieDatas = {
                name: _filter,
                type: 'gauge',
                detail: { formatter: '{value}' },
                data: _dataForGauge
            };

            if (this.config.max && this.config.max) {
                _serieDatas['min'] = this.config.min;
                _serieDatas['max'] = this.config.max;
            }

            _serieS.push(_serieDatas);

            var optionForGauge = {
                title: {
                    text: this.config.title,
                    subtext: this.config.subtitle,
                    x: "left"/*,
                    itemGap: -15*/
                },
                tooltip: {
                    trigger: "item",
                    formatter: "{a} <br/>{b} : {c}"
                },
                toolbox: {
                    show: true,
                    orient: 'horizontal',
                    x: 'right',
                    y: 'top',
                    color: ['#1e90ff', '#22bb22', '#4b0082', '#d2691e'],
                    backgroundColor: 'rgba(0,0,0,0)',
                    borderColor: '#ccc',
                    borderWidth: 0,
                    padding: 5,
                    showTitle: true,
                    feature: {
                        restore: {
                            show: true,
                            title: _featureLabels.restoreTitle,
                            color: 'black'
                        },
                        saveAsImage: {
                            show: true,
                            title: _featureLabels.saveAsImageTitle,
                            type: 'jpeg'
                        }
                    }
                },
                series: _serieS
            };

            _echartObj.setTheme(_themes[this.config.theme]);
            _echartObj.setOption(optionForGauge, true);
            _echartObj.hideLoading();
        }
    });


    /**
     *  echart-gauge-advanced
     *
     *	Visual that handle a graphic gauge with echart
     * 
     *  @since 		0.1.0
     */
    datatransformer.addVisual("echart-gauge-advanced",
    {
        title: { label: "title", type: String, required: true, order: 1 },
        subtitle: { label: "subtitle", type: String, required: false, order: 2 },
        group: { label: "group", type: datatransformer.typeGroup, required: true, order: 3 },
        filter: { label: "filter group", type: String, required: true, order: 4 },
        measure: { label: "measures principal", type: datatransformer.typeMeasure, required: true, order: 5 },
        measures: { label: "measures categories", type: datatransformer.typeMultipleMeasures, required: true, order: 6 },
        theme: { label: "theme", type: datatransformer.typeEnum, values: _themeList, required: true, order: 7 },
        colors: { label: "color (red,#ff4500)", type: String, order: 8 },
        split: { label: "split", type: Number, required: true, order: 9 },
        horizontal: { label: "horizontal", type: Boolean, order: 10 },
        horRadius: { label: "horizontal radius", type: Number, order: 11 }
    },
    function () {
        this.render = function () {
            var _echartObj = echarts.init(document.getElementById(this.config.elemId)),
				_util = this.util,
				_data = this.data.data,
				_group = this.config.group,
				_filter = this.config.filter,
				_filterObj = {},
				_measureObj = {},
				_dataOptionsObj = {},
				_generateData = [],
				_dataForGauge = [],
				_categories = [];

            _echartObj.showLoading({ text: _message.loading });

            function _getSeparetor(avgNumber, topNumber) {
                var _o = [];
                var idx = 0;

                _o.push(avgNumber);

                while (_round(_o[idx], 0) < topNumber) {
                    _o.push(_round(_o[idx] + avgNumber, 14));
                    idx++;
                }

                _o[_o.length - 1] = _round
				(_o[_o.length - 1], 0);

                return _o;
            }

            // Primary
            _measureObj[this.config.measure] = this.data.options.measures[this.config.measure];
            //"max ( "+this.util.generateMeasureColumn(this.config.measure)+" )";

            _filterObj[_group] = _filter;

            _dataOptionsObj = {
                groups: [_group],
                filters: _filterObj,
                measures: _measureObj
            };

            _generateData = this.util.generateDataTransformed(_data, _dataOptionsObj);

            _dataForGauge.push(
			{
			    value: _toFixed(_generateData[0][this.config.measure] || 0),  //_generateData.filter(function(x){ return x[_group] == _filter})[0][this.config.measure] || 0, 
			    name: _filter
			});

            //Category
            var _measuresObj = {}, _leyends = [], _datasOptionsObj = {}, _generateDatas = [], _generateDataNumber = [];

            for (var m in this.config.measures) {
                _measuresObj[this.config.measures[m]] = this.data.options.measures[this.config.measures[m]];
                //"max ( "+this.util.generateMeasureColumn(this.config.measures[m])+" )";	
                _leyends.push(this.config.measures[m]);
            }

            _datasOptionsObj = {
                groups: [_group],
                filters: _filterObj,
                measures: _measuresObj
            };

            _generateDatas = this.util.generateDataTransformed(_data, _datasOptionsObj);

            for (var k in _leyends) {
                _generateDataNumber.push(_generateDatas[0][_leyends[k]]);
            }

            var _max = Math.max.apply(null, _generateDataNumber);
            var _avgNumber = _max / this.config.split;
            var _gaugeNumberSeparetor = _getSeparetor(_avgNumber, _max);

            var _expressionStringStart = "(function(){ var _fun =  function (v){ switch (v+''){";
            var _expressionStringEnd = "default: return ''; } };  return _fun;  })()";

            var _colors = [];
            var _colorsArray = this.config.colors ? this.config.colors.split(",") : [];

            for (var m in this.config.measures) {
                var _m = this.config.measures[m];
                var _cValue = _generateDatas[0][_m] || 0;
                var _closestNumber = _getClosestNumber(_cValue, _gaugeNumberSeparetor)

                _expressionStringStart += 'case "' + _closestNumber + '": return "' + _m + ' - ' + _cValue.toFixed(2) + '";'

                ////[[0.1, 'lightgreen'],[0.4, 'orange'],[0.8, 'skyblue'],[1, '#ff4500']], 
                if (this.config.colors) {
                    var _gaugeNumberSeparetorLength = (_gaugeNumberSeparetor.length <= 0) ? 0 : _gaugeNumberSeparetor.length - 1

                    var _newColor = [_gaugeNumberSeparetor.indexOf(_closestNumber) / _gaugeNumberSeparetorLength, _colorsArray[m] || 'black'];
                    _colors.push(_newColor);
                }
            }

            /*
            *	function(v){
	        *     switch (v+''){
	        *          case '10': return 'cerverza 120';
	        *          case '40': return 'refre  200';
	        *          case '80': return 'agua  300';
	        *          case '100': return 'cahah  40';
	        *          default: return '';
            *  		}
            *	}
            */
            var _expressionFun = eval(_expressionStringStart + _expressionStringEnd),
				_serie = {};

            if (this.config.horizontal) {
                _serie = {
                    name: _filter,
                    type: 'gauge',
                    startAngle: 180,
                    endAngle: 0,
                    center: ['50%', '90%'],
                    radius: (this.config.horRadius || 250),
                    splitNumber: this.config.split,
                    splitLine: {
                        show: true,
                        length: 30,
                        lineStyle: {
                            color: '#333',
                            width: 2,
                            type: 'solid'
                        }
                    },
                    min: 0,
                    max: _max,
                    precision: 0,
                    axisLine: {
                        show: true,
                        lineStyle: {
                            color: _colors,
                            width: (this.config.horRadius || 250) * 0.6
                        }
                    },
                    axisTick: {
                        show: true,
                        precision: 0,
                        splitNumber: 5,
                        length: 8,   //length :12,       
                        lineStyle: {
                            color: '#333',
                            width: 1,
                            type: 'solid'
                        }
                    },
                    axisLabel: {
                        formatter: _expressionFun,
                        textStyle: {
                            color: '#000',
                            fontSize: 15,
                            fontWeight: 'bolder'
                        }
                    },
                    pointer: {
                        width: 50,
                        length: '90%',
                        color: 'rgba(255, 255, 255, 0.8)'
                    },
                    title: {
                        show: true,
                        offsetCenter: [0, '-60%'],
                        textStyle: {
                            color: '#fff',
                            fontSize: 30
                        }
                    },
                    detail: {
                        show: true,
                        backgroundColor: 'rgba(0,0,0,0)',
                        borderWidth: 0,
                        borderColor: '#ccc',
                        width: 100,
                        height: 40,
                        offsetCenter: [0, -40],
                        formatter: '{value}%',
                        textStyle: {
                            fontSize: 50
                        }
                    },
                    data: _dataForGauge
                }
            }
            else {
                _serie = {
                    name: _filter,
                    type: 'gauge',
                    center: ['50%', '50%'],
                    radius: [0, '75%'],
                    startAngle: 140,
                    endAngle: -140,
                    min: 0,
                    max: _max,
                    precision: 0,
                    splitNumber: this.config.split,
                    axisLine: {
                        show: true,
                        lineStyle: {
                            color: _colors,//[[0.1, 'lightgreen'],[0.4, 'orange'],[0.8, 'skyblue'],[1, '#ff4500']], 
                            width: 30
                        }
                    },
                    axisTick: {
                        show: true,
                        precision: 0,
                        splitNumber: 5,
                        length: 8,   //length :12,       
                        lineStyle: {
                            color: '#eee',
                            width: 1,
                            type: 'solid'
                        }
                    },
                    axisLabel: {
                        show: true,
                        precision: 0,
                        formatter: _expressionFun,
                        textStyle: {
                            color: '#333'
                        }
                    },
                    legendHoverLink: true,
                    splitLine: {
                        show: true,
                        length: 30,
                        lineStyle: {
                            color: '#eee',
                            width: 2,
                            type: 'solid'
                        }
                    },
                    pointer: {
                        length: '80%',
                        width: 8,
                        color: 'auto'
                    },
                    title: {
                        show: true,
                        offsetCenter: ['-65%', -10],
                        textStyle: {
                            color: '#333',
                            fontSize: 15
                        }
                    },
                    detail: {
                        show: true,
                        backgroundColor: 'rgba(0,0,0,0)',
                        borderWidth: 0,
                        borderColor: '#ccc',
                        width: 100,
                        height: 40,
                        offsetCenter: ['-60%', 10],
                        formatter: '{value}',
                        textStyle: {
                            color: 'auto',
                            fontSize: 30
                        }
                    },
                    data: _dataForGauge
                }
            }

            var optionForGauge = {
                title: {
                    text: this.config.title,
                    subtext: this.config.subtitle,
                    x: "left"/*,
                    itemGap: -15*/
                },
                tooltip: {
                    trigger: "item",
                    formatter: "{a} <br/>{b} : {c}"
                },
                toolbox: {
                    show: true,
                    orient: 'horizontal',
                    x: 'right',
                    y: 'top',
                    color: ['#1e90ff', '#22bb22', '#4b0082', '#d2691e'],
                    backgroundColor: 'rgba(0,0,0,0)',
                    borderColor: '#ccc',
                    borderWidth: 0,
                    padding: 5,
                    showTitle: true,
                    feature: {
                        restore: {
                            show: true,
                            title: _featureLabels.restoreTitle,
                            color: 'black'
                        },
                        saveAsImage: {
                            show: true,
                            title: _featureLabels.saveAsImageTitle,
                            type: 'jpeg'
                        }
                    }
                },
                series: [_serie]
            };

            _echartObj.setTheme(_themes[this.config.theme]);
            _echartObj.setOption(optionForGauge, true);
            _echartObj.hideLoading();
        }
    });



})($, datatransformer, echarts);
