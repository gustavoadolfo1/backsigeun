-- ================================================
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE  [bib].[Sp_SEL_clasificacion_material_detalle]

	AS
BEGIN
	SET NOCOUNT ON

	SELECT ROW_NUMBER() OVER(ORDER BY cmd.iClasiMaterialDetId) AS N_,
	 	cmd.iClasiMaterialDetId,
        cmd.iClasiMaterialId,
        cmd.cDescriClasiMaterialDet,
        cmd.cAbreviadoClasiMatDet,
        cmd.bHabilitado
			
	FROM bib.clasificacion_material_detalle AS cmd

	RETURN 1
END
GO
