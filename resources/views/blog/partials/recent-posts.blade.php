@if ($recentPosts->isNotEmpty())
    <aside class="lg:sticky lg:top-28 lg:self-start">
        <div class="bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h2 class="border-l-4 border-[var(--brand-yellow)] py-2 pl-4 text-xl font-black uppercase text-[var(--brand-blue)]">
                Recent Posts
            </h2>

            <div class="mt-5 divide-y divide-slate-200">
                @foreach ($recentPosts as $post)
                    @php
                        $postRoute = $post->type === \App\Models\SchoolContent::TYPE_ACHIEVEMENT
                            ? 'blog.prestasi.show'
                            : 'blog.berita.show';
                    @endphp

                    <a
                        href="{{ route($postRoute, $post) }}"
                        class="group flex gap-3 py-4 first:pt-2"
                    >
                        <span class="mt-0.5 shrink-0 text-xl leading-6 text-slate-400 transition group-hover:translate-x-1 group-hover:text-[var(--brand-yellow)]">
                            &raquo;
                        </span>
                        <span class="text-sm font-semibold leading-7 text-slate-700 transition group-hover:text-[var(--brand-blue)]">
                            {{ $post->title }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </aside>
@endif
