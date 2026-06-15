@if ($fields->isNotEmpty())
    <div class="mt-8 rounded-[1.75rem] border border-sky-100 bg-sky-50/60 p-5">
        <div>
            <h3 class="text-lg font-bold text-blue-950">Informasi Tambahan</h3>
            <p class="mt-1 text-sm text-slate-500">Lengkapi informasi tambahan yang diminta oleh panitia.</p>
        </div>

        <div class="mt-5 grid gap-4 md:grid-cols-2">
            @foreach ($fields as $field)
                @php
                    $inputName = 'custom_fields[' . $field->field_key . ']';
                    $inputId = 'custom_field_' . $field->field_key;
                    $value = old('custom_fields.' . $field->field_key, data_get($registration?->custom_form_data, $field->field_key));
                @endphp
                <div class="{{ $field->type === 'textarea' ? 'md:col-span-2' : '' }}">
                    <label for="{{ $inputId }}" class="text-sm font-medium text-slate-600">
                        {{ $field->label }}
                        @if ($field->is_required)
                            <span class="text-rose-500">*</span>
                        @endif
                    </label>

                    @if ($field->type === 'textarea')
                        <textarea id="{{ $inputId }}" name="{{ $inputName }}" rows="4" placeholder="{{ $field->placeholder }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-400 focus:outline-none">{{ $value }}</textarea>
                    @elseif ($field->type === 'select')
                        <select id="{{ $inputId }}" name="{{ $inputName }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-400 focus:outline-none">
                            <option value="">Pilih {{ strtolower($field->label) }}</option>
                            @foreach ($field->options ?? [] as $option)
                                <option value="{{ $option }}" @selected((string) $value === (string) $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                    @else
                        <input
                            id="{{ $inputId }}"
                            name="{{ $inputName }}"
                            type="{{ $field->type }}"
                            value="{{ $value }}"
                            placeholder="{{ $field->placeholder }}"
                            class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-400 focus:outline-none"
                        >
                    @endif

                    @if ($field->help_text)
                        <p class="mt-1 text-xs leading-5 text-slate-500">{{ $field->help_text }}</p>
                    @endif
                    @error('custom_fields.' . $field->field_key)
                        <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
        </div>
    </div>
@endif
