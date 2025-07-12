<?php

/**
 * @var \App\View\AppView $this
 */
?>
<div class="static lg:relative flex align-middle" x-data="{ open: false }">
    <button type="button" aria-label="Notifications" @click="open = !open" class="flex align-middle rounded-full relative dark:text-white hover:text-blue-600 p-2 disabled:opacity-50 disabled:cursor-not-allowed">
        <span class="material-icons">notifications</span>
        <span class="absolute top-0 right-0 inline-block w-2 h-2 bg-orange-500 rounded-full"></span>
    </button>
    <div x-show="open" x-cloak @click.away="open = false" x-transition class="absolute right-5 lg:right-0 mt-10 w-96 z-99 bg-white dark:bg-neutral-800 dark:text-white border border-neutral-200 dark:border-neutral-700 rounded shadow-lg z-50">
        <div class="p-4 border-b border-neutral-200 dark:border-neutral-700 font-semibold flex items-center align-middle justify-between">
            <div><span>Notifications</span></div>
            <div class="flex items-center align-middle justify-end gap-2">
                <div class="flex justify-center">
                    <div
                        x-data="{
                            open: false,
                            toggle() {
                                if (this.open) {
                                    return this.close()
                                }

                                this.$refs.button.focus()

                                this.open = true
                            },
                            close(focusAfter) {
                                if (! this.open) return

                                this.open = false

                                focusAfter && focusAfter.focus()
                            }
                        }"
                        x-on:keydown.escape.prevent.stop="close($refs.button)"
                        x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
                        x-id="['dropdown-button']"
                        class="relative"
                    >
                        <!-- Button -->
                        <button
                            x-ref="button"
                            x-on:click="toggle()"
                            :aria-expanded="open"
                            :aria-controls="$id('dropdown-button')"
                            type="button"
                            class="relative flex items-center whitespace-nowrap justify-center p-2 rounded-lg hover:bg-neutral-100 text-neutral-800 dark:text-white dark:hover:text-neutral-800"
                        >
                            <span class="material-icons">filter_list</span>
                        </button>

                        <!-- Panel -->
                        <div
                            x-ref="panel"
                            x-show="open"
                            x-transition.origin.top.right
                            x-on:click.outside="close($refs.button)"
                            :id="$id('dropdown-button')"
                            x-cloak
                            class="absolute right-0 min-w-48 rounded-lg shadow-sm mt-2 z-10 origin-top-right bg-white dark:bg-neutral-800 p-1.5 outline-none border border-neutral-200"
                        >
                            <label class="px-2 lg:py-1.5 py-2 w-full flex items-center rounded-md transition-colors text-left text-neutral-800 hover:bg-neutral-100 focus-visible:bg-neutral-100 dark:text-white dark:hover:text-neutral-800 disabled:opacity-50 disabled:cursor-not-allowed">
                                <input name="show_unread" id="show-unread" type="checkbox" value="1" checked class="sr-only peer">
                                <div class="relative w-11 h-6 bg-neutral-200 peer-focus:outline-none peer-focus:ring-1 peer-focus:ring-neutral-300 dark:peer-focus:ring-neutral-800 rounded-full peer dark:bg-neutral-800 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white dark:after:text-neutral-800 after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-neutral-800 peer-checked:bg-neutral-800 dark:peer-checked:bg-neutral-600"></div>
                                <span class="ms-3 text-sm font-medium text-neutral-800 dark:text-white dark:hover:text-neutral-800 dark:after::text-neutral-800">Show unread</span>
                            </label>
                            <label class="px-2 lg:py-1.5 py-2 w-full flex items-center rounded-md transition-colors text-left text-neutral-800 hover:bg-neutral-100 dark:text-white dark:hover:text-neutral-800  focus-visible:bg-neutral-100 disabled:opacity-50 disabled:cursor-not-allowed">
                                <input name="only_important" id="only-important" type="checkbox" value="" class="sr-only peer">
                                <div class="relative w-11 h-6 bg-neutral-200 peer-focus:outline-none peer-focus:ring-1 peer-focus:ring-neutral-300 dark:peer-focus:ring-neutral-800 rounded-full peer dark:bg-neutral-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white dark:after:text-neutral-800 after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-neutral-800 peer-checked:bg-neutral-800 dark:peer-checked:bg-neutral-600"></div>
                                <span class="ms-3 text-sm font-medium text-neutral-800 dark:hover:text-neutral-800 dark:text-white dark:after::text-neutral-800">Only important</span>
                            </label>
                        </div>
                    </div>
                </div>
                <button class="relative flex items-center whitespace-nowrap justify-center p-2 rounded-lg hover:bg-neutral-100 text-neutral-800 dark:text-white dark:hover:text-neutral-800"><span class="material-icons">launch</span></button>
                <div class="flex justify-center">
                    <div
                        x-data="{
                            open: false,
                            toggle() {
                                if (this.open) {
                                    return this.close()
                                }

                                this.$refs.button.focus()

                                this.open = true
                            },
                            close(focusAfter) {
                                if (! this.open) return

                                this.open = false

                                focusAfter && focusAfter.focus()
                            }
                        }"
                        x-on:keydown.escape.prevent.stop="close($refs.button)"
                        x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
                        x-id="['dropdown-button']"
                        class="relative"
                    >
                        <!-- Button -->
                        <button
                            x-ref="button"
                            x-on:click="toggle()"
                            :aria-expanded="open"
                            :aria-controls="$id('dropdown-button')"
                            type="button"
                            class="relative flex items-center whitespace-nowrap justify-center p-2 rounded-lg hover:bg-neutral-100 text-neutral-800 dark:text-white dark:hover:text-neutral-800"
                        >
                            <span class="material-icons rotate-90">more_horiz</span>
                        </button>

                        <!-- Panel -->
                        <div
                            x-ref="panel"
                            x-show="open"
                            x-transition.origin.top.right
                            x-on:click.outside="close($refs.button)"
                            :id="$id('dropdown-button')"
                            x-cloak
                            class="absolute right-0 min-w-48 rounded-lg shadow-sm mt-2 z-10 origin-top-right bg-white dark:bg-neutral-800 p-1.5 outline-none border border-neutral-200"
                        >
                            <a href="#new" class="px-2 lg:py-1.5 py-2 w-full flex items-center align-middle rounded-md gap-2 transition-colors text-left text-neutral-800 dark:text-white dark:hover:text-neutral-800 hover:bg-neutral-100 focus-visible:bg-neutral-100 disabled:opacity-50 disabled:cursor-not-allowed text-sm">
                            <span class="material-icons">check</span>MarK all as read
                            </a>
                            <a href="#edit" class="px-2 lg:py-1.5 py-2 w-full flex items-center align-middle gap-2 rounded-md transition-colors text-left text-neutral-800 dark:text-white dark:hover:text-neutral-800 hover:bg-neutral-100 focus-visible:bg-neutral-100 disabled:opacity-50 disabled:cursor-not-allowed text-sm">
                            <span class="material-icons">feedback</span>Give feedback
                            </a>
                            <a href="#delete" class="px-2 lg:py-1.5 py-2 w-full flex items-center align-middle gap-2 rounded-md transition-colors text-left text-neutral-800  dark:text-white dark:hover:text-neutral-800 hover:bg-neutral-100 focus-visible:bg-neutral-100 disabled:opacity-50 disabled:cursor-not-allowed text-sm">
                            <span class="material-icons">settings</span>Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <ul class="p-2">
            <li class="flex items-center gap-3 px-4 py-3 hover:bg-neutral-100 dark:hover:bg-neutral-800 rounded-lg">
                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-orange-100 text-orange-700 font-bold">C</span>
                <div>
                    <div class="text-sm font-medium">CodeRabbit commented on a pull request</div>
                    <div class="text-xs text-neutral-1000 dark:text-neutral-200">2 days ago</div>
                    <div class="text-xs text-neutral-1000 dark:text-neutral-200">#comment Fix: Email issue fixed</div>
                </div>
                <span class="ml-auto w-2 h-2 bg-blue-500 rounded-full"></span>
            </li>
            <li class="flex items-center gap-3 px-4 py-3 hover:bg-neutral-100 dark:hover:bg-neutral-800 rounded-lg">
                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-orange-100 text-orange-700 font-bold">C</span>
                <div>
                    <div class="text-sm font-medium">CodeRabbit commented on a pull request</div>
                    <div class="text-xs text-neutral-1000 dark:text-neutral-200">2 days ago</div>
                    <div class="text-xs text-neutral-1000 dark:text-neutral-200">#comment Fix: Email issue fixed</div>
                </div>
                <span class="ml-auto w-2 h-2 bg-green-500 rounded-full"></span>
            </li>
            <li class="flex items-center gap-3 px-4 py-3 hover:bg-neutral-100 dark:hover:bg-neutral-800 rounded-lg">
                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-orange-100 text-orange-700 font-bold">C</span>
                <div>
                    <div class="text-sm font-medium">CodeRabbit commented on a pull request</div>
                    <div class="text-xs text-neutral-1000 dark:text-neutral-200">2 days ago</div>
                    <div class="text-xs text-neutral-1000 dark:text-neutral-200">#comment Fix: Email issue fixed</div>
                </div>
                <span class="ml-auto w-2 h-2 bg-red-500 rounded-full"></span>
            </li>
        </ul>
    </div>
</div>
