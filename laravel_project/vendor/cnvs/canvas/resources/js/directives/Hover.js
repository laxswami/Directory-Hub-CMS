/**
 * Registered locally and uses 'bind' and 'unbind' events to add/remove
 * event listeners to/from DOM Element. Mouseover <div> element.
 *
 * @author David Hayden
 * @link https://codepen.io/koderdojo/pen/pwEGpX
 */
const Hover = {
    bind: function(el, binding, vnode) {
        el.addEventListener('mouseenter', function() {
            el.classList.add(binding.value.class)
        });

        el.addEventListener('mouseleave', function() {
            el.classList.remove(binding.value.class)
        });
    }
}

export default Hover
