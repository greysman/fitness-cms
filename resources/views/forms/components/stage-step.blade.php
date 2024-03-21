<label
    aria-labelledby="{{ $getId() }}"
    id="{{ $getId() }}-{{ $value }}"
    x-ref="step-{{ $getId() }}"
    role="tabpanel"
    tabindex="0"
    x-bind:class="{ 'invisible h-0 overflow-y-hidden': step !== @js($getId()) }"
    x-on:expand-concealing-component.window="
        error = $el.querySelector('[data-validation-error]')

        if (! error) {
            return
        }

        if (! isStepAccessible(step, @js($getId()))) {
            return
        }

        step = @js($getId())

        if (document.body.querySelector('[data-validation-error]') !== error) {
            return
        }
    "
    {{ $attributes->merge($getExtraAttributes())->class(['filament-forms-wizard-component-step outline-none']) }}
>
    {{ $getChildComponentContainer() }}
</label>
