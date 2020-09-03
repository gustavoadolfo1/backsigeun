-- ================================================
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [bib].[Sp_SEL_clasificacion_material]
	
AS
BEGIN
	SET NOCOUNT ON

	SELECT ROW_NUMBER() OVER(ORDER BY cm.iClasiMaterialId) AS N_,
	 	cm.iClasiMaterialId
       ,cm.cDescriMaterial
       ,cm.cAbreviadoClasiMat
       ,cm.bHabilitado
			
	FROM bib.clasificacion_material AS cm

	RETURN 1
END
GO
