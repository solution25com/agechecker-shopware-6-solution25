import AgeCheckerPlugin from './age-checker-plugin/age-checker-plugin';

const PluginManager = window.PluginManager;
PluginManager.register('AgeCheckerPlugin', AgeCheckerPlugin, '[age-checker-plugin]');
