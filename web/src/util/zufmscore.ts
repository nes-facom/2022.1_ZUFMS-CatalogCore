import zufmsCoreSchema from "@/assets/zufmscore.schema.json";
import zufmsCoreMetadataSchema from "@/assets/zufmscore.metadata.schema.json";

export const sections =
  zufmsCoreSchema["properties"]["artificial:section"]["examples"];

export const termclassDescriptions =
  zufmsCoreMetadataSchema["properties"]["$zufmscore:termclass"][
    "$zufmscore:termclassDescription"
  ];

export const terms = zufmsCoreSchema["properties"];

const { sizes, counter } = Object.values(terms).reduce(
  ({ counter, lastTermclass, sizes }, value, index) => {
    const termclassName = value["$zufmscore:termclass"];
    const termclassCounter =
      (counter[termclassName] ?? 0) + (termclassName !== lastTermclass ? 1 : 0);

    const isNewTermclass = lastTermclass !== termclassName;

    const { name, start, size } = isNewTermclass
      ? { name: termclassName, start: index, size: 0 }
      : sizes[sizes.length - 1];

    return {
      counter: { ...counter, [termclassName]: termclassCounter },
      lastTermclass: termclassName,
      sizes: [
        ...(isNewTermclass ? sizes : sizes.slice(0, -1)),
        {
          name,
          start,
          counter: termclassCounter,
          size: size + 1,
        },
      ],
    };
  },
  {
    counter: {} as Record<string, number>,
    lastTermclass: "",
    sizes: [] as {
      name: string;
      start: number;
      counter: number;
      size: number;
    }[],
  }
);

export const termclassSizes = { sizes, counter };
