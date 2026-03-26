import antfu from '@antfu/eslint-config';
import pluginSimpleImportSort from 'eslint-plugin-simple-import-sort';
import pluginTurbo from 'eslint-plugin-turbo';

export const baseJSRules = {
  plugins: {
    'simple-import-sort' : pluginSimpleImportSort,
    'turbo'              : pluginTurbo,
  },
  rules: {
    'style/space-in-parens' : [ 'error', 'always' ],
    'semi'                  : [
      'error',
      'always',
      {
        omitLastInOneLineBlock     : false,
        omitLastInOneLineClassBody : false,
      },
    ],
    'style/semi'              : [ 'error', 'always' ],
    'curly'                   : [ 'error', 'multi-line' ],
    'style/indent'            : 'off',
    'style/indent-binary-ops' : 'off',
    'style/no-tabs'           : 'off',
    'style/no-multi-spaces'   : [ 'warn', {
      ignoreEOLComments : false,
      exceptions        : {
        VariableDeclarator   : true,
        ImportDeclaration    : true,
        Property             : true,
        AssignmentExpression : true,
      },
    } ],
    'style/key-spacing': [ 'error', {
      align: {
        beforeColon : true,
        afterColon  : true,
        on          : 'colon',
      },
    } ],
    'style/array-bracket-spacing'      : [ 'error', 'always' ],
    'style/object-curly-spacing'       : [ 'error', 'always' ],
    'no-unused-vars'                   : 'off',
    'unused-imports/no-unused-imports' : 'off',
    'unused-imports/no-unused-vars'    : [
      'warn',
      {
        vars              : 'all',
        varsIgnorePattern : '^_',
        args              : 'after-used',
        argsIgnorePattern : '^_',
      },
    ],
    'style/brace-style'          : [ 'error', '1tbs', { allowSingleLine: true } ],
    'node/prefer-global/process' : 'off',
    // sort rule : start
    'perfectionist/sort-imports' : 'off',
    'sort-imports'               : 'off',
    'import/order'               : 'off',
    'simple-import-sort/imports' : [
      'error',
      {
        groups: [
          // 1. Node.js built-ins
          [ '^node:' ],
          // 2. External packages
          [ '^@?\\w' ],
          // Side-effect imports: env bootstrap (handled manually, see below)
          [ '^\\u0000' ], // side-effect imports generally
          // 3. Aliased internal packages
          [ '^lib/' ],
          // 4. Relative imports (./ or ../)
          [ '^\\.' ],
          // 5. Catch-all fallback
          [ '.' ],
        ],
      },
    ],
    'simple-import-sort/exports': 'off',
  },
};

export default antfu(
  {
    typescript : false,
    jsx        : false, // Explicitly disable JSX handling in root config
    ignores    : [
      '**/build/**',
      '**/dist/**',
      '**/public/**',
      '**/node_modules/**',
      '**/package.json',
    ],
  },
).append( baseJSRules );
