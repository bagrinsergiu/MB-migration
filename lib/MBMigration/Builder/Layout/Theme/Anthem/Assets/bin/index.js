import esbuild from "esbuild";
import Minimist from "minimist";

const argv_ = Minimist(process.argv.slice(2));
const IS_PRODUCTION = Boolean(argv_.production);
const WATCH = Boolean(argv_.watch);

const define = {
  "process.env": JSON.stringify("{}"),
  "process.env.IS_PRODUCTION": JSON.stringify(IS_PRODUCTION)
};

const entryPoints = ["Menu", "Text"].map((p) => `src/${p}/index.ts`);

esbuild
  .build({
    bundle: true,
    loader: { ".ts": "ts" },
    minify: IS_PRODUCTION,
    sourcemap: IS_PRODUCTION ? false : "inline",
    define,
    outdir: "dist",
    entryNames: "[dir]",
    entryPoints: entryPoints
  })
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
