import packageJson from 'eslint-plugin-package-json';

export default [
  packageJson.configs.recommended,
  {
    files : [ '**/package.json' ],
    rules : {
      ...packageJson.configs.recommended.rules,
      'package-json/require-type'      : 'warn',
      'package-json/valid-description' : 'warn',
      'package-json/order-properties'  : [
        'warn',
        {
          order: [
            'archiveName', // custom
            'name',
            'version',
            'private',
            'type',
            'workspaces',
            'publishConfig',
            'description',
            'author',
            'contributors',
            'license',
            'homepage',
            'repository',
            'bugs',
            'copyright', // custom
            'keywords',
            'main',
            'pot',
            'app', // custom
            'plugin', // custom
            'exports',
            'browser',
            'files',
            'bin',
            'man',
            'directories',
            'scripts',
            'funding',
            'config',
            'dependencies',
            'devDependencies',
            'peerDependencies',
            'optionalDependencies',
            'bundledDependencies',
            'overrides',
            'engineStrict',
            'engines',
            'os',
            'cpu',
          ],
        },
      ],
      'package-json/sort-collections': [
        'error',
        [
          // "scripts",
          'dependencies',
          'devDependencies',
          'peerDependencies',
          // "config",
        ],
      ],
      'package-json/valid-package-def'   : 'off',
      // ✅ Turn off conflicting JSONC rule — no import needed
      'jsonc/sort-keys'                  : 'off',
      // `description` won't be required in package.json with `"private": true`
      'package-json/require-description' : 'error',
    },
  },
];
