import pandas as pd
from glob import glob
import os

os.system(
    "libreoffice --headless --convert-to csv --infilter=CSV:44,34,76,1 infra/database/seed/colecoes/*.{xlsx,xls} --outdir infra/database/seed/csv")

files_path = "infra/database/seed/csv/*.csv"

files = glob(files_path)


def get_section_from_filepath(filepath):
    basename = os.path.basename(filepath)
    filename = os.path.splitext(basename)[0]
    return filename.removeprefix("ZUFMS")


def csv_to_df(file):
    return pd.read_csv(file, header=1).assign(
        **{'artificial:section': get_section_from_filepath(file)})


all_dfs = [csv_to_df(file) for file in files]

df = pd.concat(all_dfs, ignore_index=True)

df.to_csv('./all.csv', index=False)
