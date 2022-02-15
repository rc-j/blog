class Node {
    constructor(props) {
        this.tag = props.tag
        this.html = props.html
        this.attrs = props.attrs
        this.events = props.events
        this.parent = props.parent
    }
    create() {
        let elem = document.createElement(this.tag)
        elem.innerHTML = this.html
        for (let attr in this.attrs) {
            elem.setAttribute(attr, this.attrs[attr]);
        }
        for (let prop in this.events) {
            elem.addEventListener(prop, this.events[prop]);
        }
        document.getElementById(this.parent).appendChild(elem)
    }
}