function $(id, winHDL) {
    if (typeof(winHDL) === "undefined") {
        winHDL = window;
    }
    return winHDL.document.getElementById(id);
}

Array.prototype.shift = function () {
    var firstUnit = this[0];
    for (var i = 0; i < this.length; i++)
    {
        this[i] = this[i+1];
    }
    this.pop();

    return firstUnit;
}