import esbuild from "esbuild";
import Minimist from "minimist";

const argv_ = Minimist(process.argv.slice(2));
const IS_PRODUCTION = Boolean(argv_.production);
const WATCH = Boolean(argv_.watch);

const entryPoints = ["Menu", "Text", "StyleExtractor"];

const baseConfig = {
  bundle: true,
  loader: { ".ts": "ts" },
  minify: IS_PRODUCTION,
  sourcemap: IS_PRODUCTION ? false : "inline",
  outdir: "dist",
  outbase: "src",
  entryNames: "[dir]"
};

entryPoints.forEach((entry) => {
  const config = {
    ...baseConfig,
    globalName: `scripts["${entry}"]`,
    entryPoints: [`src/${entry}/index.ts`]
  };

  esbuild
    .build(config)
    .then(async () => {
      if (WATCH) {
        console.log("SDK Client: ⚾ Watching for changes...");
        let ctx = await esbuild.context(config);

        await ctx.watch();
      } else {
        console.log("SDK Client: ⚡ Done");
      }
    })
    .catch(() => process.exit(1));
});
